<?php

namespace App\controllers;

use App\db\Database;
use App\helpers\Response;
use App\security\Csrf;

class CourseController
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function list(): void
    {
        $pdo = Database::pdo();
        $courses = $pdo->query('SELECT c.*, cat.name AS category_name FROM courses c LEFT JOIN categories cat ON cat.id = c.category_id ORDER BY c.created_at DESC')->fetchAll();
        $this->render('courses', ['title' => 'Courses', 'courses' => $courses]);
    }

    public function detail(string $slug): void
    {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare('SELECT * FROM courses WHERE slug = :slug LIMIT 1');
        $stmt->execute([':slug' => $slug]);
        $course = $stmt->fetch();
        if (!$course) {
            http_response_code(404);
            echo 'Course not found';
            return;
        }

        $topics = $pdo->prepare('SELECT * FROM topics WHERE course_id = :cid ORDER BY order_index ASC');
        $topics->execute([':cid' => $course['id']]);
        $topics = $topics->fetchAll();

        $subtopicsByTopic = [];
        foreach ($topics as $t) {
            $st = $pdo->prepare('SELECT * FROM subtopics WHERE topic_id = :tid');
            $st->execute([':tid' => $t['id']]);
            $subtopicsByTopic[$t['id']] = $st->fetchAll();
        }

        $this->render('course_detail', compact('course', 'topics', 'subtopicsByTopic') + ['title' => $course['title']]);
    }

    public function validateReferral(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            return Response::json(['ok' => false, 'error' => 'Invalid CSRF'], 400);
        }

        $code = trim($_POST['code'] ?? '');
        if ($code === '') return Response::json(['ok' => false, 'error' => 'Code required'], 422);

        $pdo = Database::pdo();
        $stmt = $pdo->prepare('SELECT id FROM users WHERE referral_code = :code LIMIT 1');
        $stmt->execute([':code' => $code]);
        $exists = (bool)$stmt->fetch();
        return Response::json(['ok' => $exists]);
    }

    public function purchaseCourse(): void
    {
        if (empty($_SESSION['user'])) return Response::json(['ok' => false, 'error' => 'Unauthorized'], 401);
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) return Response::json(['ok' => false, 'error' => 'Invalid CSRF'], 400);

        $courseId = (int)($_POST['course_id'] ?? 0);
        $referral = trim($_POST['referral_code'] ?? '');
        $pdo = Database::pdo();

        // Ensure course exists
        $c = $pdo->prepare('SELECT * FROM courses WHERE id = :id');
        $c->execute([':id' => $courseId]);
        $course = $c->fetch();
        if (!$course) return Response::json(['ok' => false, 'error' => 'Course not found'], 404);

        // Validate referral required
        $refOk = false;
        if ($referral !== '') {
            $s = $pdo->prepare('SELECT id FROM users WHERE referral_code = :code');
            $s->execute([':code' => $referral]);
            $refOk = (bool)$s->fetch();
        }
        if (!$refOk && (!($_SESSION['user']['is_admin'] ?? 0))) {
            return Response::json(['ok' => false, 'error' => 'Valid referral code required'], 422);
        }

        // Create pending purchase
        $ins = $pdo->prepare('INSERT INTO purchases (user_id, course_id, amount, payment_status, payment_txn_id, created_at) VALUES (:uid, :cid, :amount, "pending", NULL, NOW())');
        $ins->execute([':uid' => $_SESSION['user']['id'], ':cid' => $courseId, ':amount' => $course['price']]);
        $purchaseId = (int)$pdo->lastInsertId();

        $upi = $this->config['merchant_vpa'];
        $pn = rawurlencode($this->config['merchant_name']);
        $am = number_format((float)$course['price'], 2, '.', '');
        $tn = rawurlencode('Course Payment #' . $purchaseId);
        $upiLink = "upi://pay?pa={$upi}&pn={$pn}&am={$am}&tn={$tn}";

        return Response::json([
            'ok' => true,
            'purchase_id' => $purchaseId,
            'upi_link' => $upiLink,
            'qr_text' => $upiLink,
        ]);
    }

    public function verifyPayment(): void
    {
        // Stub for webhook or manual verification
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            return Response::json(['ok' => false, 'error' => 'Invalid CSRF'], 400);
        }
        $purchaseId = (int)($_POST['purchase_id'] ?? 0);
        $txnId = trim($_POST['payment_txn_id'] ?? '');

        $pdo = Database::pdo();
        $upd = $pdo->prepare('UPDATE purchases SET payment_txn_id = :txn WHERE id = :id');
        $upd->execute([':txn' => $txnId ?: null, ':id' => $purchaseId]);

        return Response::json(['ok' => true]);
    }

    private function render(string $view, array $data = []): void
    {
        extract($data);
        $config = $this->config;
        $user = $_SESSION['user'] ?? null;
        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/' . $view . '.php';
        include __DIR__ . '/../views/layout/footer.php';
    }
}

