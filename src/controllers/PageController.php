<?php

namespace App\controllers;

use App\helpers\Response;

class PageController
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function landing(): void
    {
        $this->render('landing', [
            'title' => 'KARMAPRENEUR.IN',
        ]);
    }

    public function dashboard(): void
    {
        if (empty($_SESSION['user'])) {
            Response::redirect('/login');
        }
        $this->render('dashboard', [
            'title' => 'Dashboard',
        ]);
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

