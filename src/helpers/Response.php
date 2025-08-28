<?php

namespace App\helpers;

class Response
{
    public static function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public static function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}

