<?php

namespace App\controllers;

use App\db\Database;

class HistoryController
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function index(): void
    {
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
        $pdo = Database::pdo();
        $stmt = $pdo->prepare('SELECT * FROM activity_history WHERE user_id = :id ORDER BY created_at DESC LIMIT 200');
        $stmt->execute([':id' => $_SESSION['user']['id']]);
        $history = $stmt->fetchAll();

        $config = $this->config;
        $user = $_SESSION['user'];
        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/history.php';
        include __DIR__ . '/../views/layout/footer.php';
    }
}

