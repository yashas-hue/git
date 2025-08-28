<?php

namespace App\controllers;

use App\db\Database;
use App\helpers\Response;
use App\security\Csrf;

class AccountController
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function show(): void
    {
        if (empty($_SESSION['user'])) Response::redirect('/login');
        $this->render('account', ['title' => 'Account']);
    }

    public function update(): void
    {
        if (empty($_SESSION['user'])) return Response::json(['ok' => false, 'error' => 'Unauthorized'], 401);
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) return Response::json(['ok' => false, 'error' => 'Invalid CSRF'], 400);

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        $pdo = Database::pdo();
        $upd = $pdo->prepare('UPDATE users SET name = :name, email = :email WHERE id = :id');
        $upd->execute([':name' => $name, ':email' => $email ?: null, ':id' => $_SESSION['user']['id']]);

        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email ?: null;

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

