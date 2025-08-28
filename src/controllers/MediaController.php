<?php

namespace App\controllers;

use App\db\Database;
use App\helpers\Response;

class MediaController
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function token(): void
    {
        if (empty($_SESSION['user'])) return Response::json(['ok' => false, 'error' => 'Unauthorized'], 401);
        $mediaId = (int)($_GET['media_id'] ?? 0);

        $pdo = Database::pdo();
        $stmt = $pdo->prepare('SELECT * FROM media WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $mediaId]);
        $media = $stmt->fetch();
        if (!$media) return Response::json(['ok' => false, 'error' => 'Not found'], 404);

        $expiry = time() + (int)$this->config['media_token_ttl_seconds'];
        $payload = base64_encode(json_encode(['uid' => $_SESSION['user']['id'], 'mid' => $mediaId, 'exp' => $expiry]));
        $sig = hash_hmac('sha256', $payload, $this->config['media_token_secret']);

        // In practice, return a tokenized HLS URL
        $token = $payload . '.' . $sig;
        $signedUrl = '/secure/media/' . $mediaId . '/master.m3u8?token=' . urlencode($token);
        return Response::json(['ok' => true, 'url' => $signedUrl]);
    }
}

