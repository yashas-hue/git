<?php

namespace App\controllers;

use App\db\Database;
use App\helpers\Response;
use App\security\Csrf;

class AdminController
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    private function requireAdmin(): void
    {
        if (empty($_SESSION['user']) || (int)$_SESSION['user']['is_admin'] !== 1) {
            Response::redirect('/login');
        }
    }

    public function dashboard(): void
    {
        $this->requireAdmin();
        $pdo = Database::pdo();
        $pendingPurchases = $pdo->query('SELECT p.*, u.name as user_name, c.title as course_title FROM purchases p JOIN users u ON u.id = p.user_id JOIN courses c ON c.id = p.course_id WHERE p.payment_status = "pending" ORDER BY p.created_at DESC')->fetchAll();
        $config = $this->config;
        $user = $_SESSION['user'];
        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/admin_dashboard.php';
        include __DIR__ . '/../views/layout/footer.php';
    }

    public function verifyPayment(): void
    {
        $this->requireAdmin();
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) return Response::json(['ok' => false, 'error' => 'Invalid CSRF'], 400);
        $purchaseId = (int)($_POST['purchase_id'] ?? 0);
        $txnId = trim($_POST['txn_id'] ?? '');
        $pdo = Database::pdo();
        $upd = $pdo->prepare('UPDATE purchases SET payment_status = "paid", payment_txn_id = :txn WHERE id = :id');
        $upd->execute([':txn' => $txnId ?: null, ':id' => $purchaseId]);
        return Response::json(['ok' => true]);
    }
}

