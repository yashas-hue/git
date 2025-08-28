<?php

namespace App\controllers;

use App\db\Database;
use App\helpers\Response;
use App\security\Csrf;

class FormsController
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function bookConsult(): void
    {
        if (empty($_SESSION['user'])) return Response::json(['ok' => false, 'error' => 'Unauthorized'], 401);
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) return Response::json(['ok' => false, 'error' => 'Invalid CSRF'], 400);

        $category = trim($_POST['category'] ?? '');
        $details = trim($_POST['details'] ?? '');
        $mode = ($_POST['preferred_mode'] ?? 'online') === 'offline' ? 'offline' : 'online';

        $pdo = Database::pdo();
        $ins = $pdo->prepare('INSERT INTO consult_requests (user_id, category, details, preferred_mode, payment_status, created_at) VALUES (:uid, :cat, :det, :mode, "pending", NOW())');
        $ins->execute([':uid' => $_SESSION['user']['id'], ':cat' => $category, ':det' => $details, ':mode' => $mode]);

        return Response::json(['ok' => true]);
    }

    public function orderProduct(): void
    {
        if (empty($_SESSION['user'])) return Response::json(['ok' => false, 'error' => 'Unauthorized'], 401);
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) return Response::json(['ok' => false, 'error' => 'Invalid CSRF'], 400);

        $productId = (int)($_POST['product_id'] ?? 0);
        $address = [
            'line1' => trim($_POST['line1'] ?? ''),
            'line2' => trim($_POST['line2'] ?? ''),
            'city' => trim($_POST['city'] ?? ''),
            'state' => trim($_POST['state'] ?? ''),
            'zip' => trim($_POST['zip'] ?? ''),
        ];

        $pdo = Database::pdo();
        $p = $pdo->prepare('SELECT * FROM products WHERE id = :id');
        $p->execute([':id' => $productId]);
        $product = $p->fetch();
        if (!$product) return Response::json(['ok' => false, 'error' => 'Product not found'], 404);

        $ins = $pdo->prepare('INSERT INTO orders (user_id, product_id, address, amount, payment_status, created_at) VALUES (:uid, :pid, :addr, :amt, "pending", NOW())');
        $ins->execute([':uid' => $_SESSION['user']['id'], ':pid' => $productId, ':addr' => json_encode($address), ':amt' => $product['price']]);

        return Response::json(['ok' => true, 'order_id' => (int)$pdo->lastInsertId()]);
    }

    public function forgotPassword(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) return Response::json(['ok' => false, 'error' => 'Invalid CSRF'], 400);

        $contact = trim($_POST['contact'] ?? '');
        $contactType = filter_var($contact, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $pdo = Database::pdo();
        $ins = $pdo->prepare('INSERT INTO forgot_password_requests (user_id_or_contact, contact_type, message, status, created_at) VALUES (:c, :t, :m, "open", NOW())');
        $ins->execute([':c' => $contact, ':t' => $contactType, ':m' => trim($_POST['message'] ?? '')]);

        return Response::json(['ok' => true]);
    }
}

