<?php

namespace App\controllers;

use App\db\Database;
use App\helpers\Response;
use App\helpers\Validator;
use App\security\Csrf;
use PDO;

class AuthController
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function showSignup(): void
    {
        $this->render('signup', ['title' => 'Sign Up']);
    }

    public function showLogin(): void
    {
        $this->render('login', ['title' => 'Login']);
    }

    public function signup(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            http_response_code(400);
            echo 'Invalid CSRF token';
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $phone = preg_replace('/\D+/', '', $_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $referralInput = trim($_POST['referral_code'] ?? '');

        $errors = [];
        if ($name === '') $errors['name'] = 'Name is required';
        if (!Validator::isValidPhone($phone)) $errors['phone'] = 'Invalid phone';
        if (!Validator::isValidUsername($username)) $errors['username'] = 'Invalid username';
        if (!Validator::isValidPassword($password)) $errors['password'] = 'Weak password';
        if (!Validator::isValidEmail($email)) $errors['email'] = 'Invalid email';

        if ($errors) {
            $this->render('signup', compact('errors', 'name', 'phone', 'email', 'username', 'referralInput'));
            return;
        }

        $pdo = Database::pdo();

        // Uniques check
        $stmt = $pdo->prepare('SELECT id, phone, email, username FROM users WHERE phone = :phone OR email = :email OR username = :username LIMIT 1');
        $stmt->execute([':phone' => $phone, ':email' => $email ?: null, ':username' => $username]);
        if ($row = $stmt->fetch()) {
            if ($row['phone'] === $phone) $errors['phone'] = 'Phone already in use';
            if (!empty($email) && $row['email'] === $email) $errors['email'] = 'Email already in use';
            if ($row['username'] === $username) $errors['username'] = 'Username already taken';
            $this->render('signup', compact('errors', 'name', 'phone', 'email', 'username', 'referralInput'));
            return;
        }

        $passwordHash = password_hash($password, defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_BCRYPT);

        // Handle referral
        $referredBy = null;
        if ($referralInput !== '') {
            $stmt = $pdo->prepare('SELECT id FROM users WHERE referral_code = :code');
            $stmt->execute([':code' => $referralInput]);
            $referrer = $stmt->fetch();
            if ($referrer) {
                $referredBy = (int)$referrer['id'];
            } else {
                $errors['referral_code'] = 'Referral code not found';
                $this->render('signup', compact('errors', 'name', 'phone', 'email', 'username', 'referralInput'));
                return;
            }
        }

        // Insert user
        $pdo->beginTransaction();
        $stmt = $pdo->prepare('INSERT INTO users (name, phone, email, username, password_hash, referred_by, created_at) VALUES (:name, :phone, :email, :username, :password_hash, :referred_by, NOW())');
        $stmt->execute([
            ':name' => $name,
            ':phone' => $phone,
            ':email' => $email ?: null,
            ':username' => $username,
            ':password_hash' => $passwordHash,
            ':referred_by' => $referredBy,
        ]);
        $userId = (int)$pdo->lastInsertId();

        // Generate referral code: KP-<4chars>-<userid>
        $four = strtoupper(substr(bin2hex(random_bytes(4)), 0, 4));
        $refCode = sprintf('KP-%s-%d', $four, $userId);
        $upd = $pdo->prepare('UPDATE users SET referral_code = :code WHERE id = :id');
        $upd->execute([':code' => $refCode, ':id' => $userId]);

        if ($referredBy) {
            $insRef = $pdo->prepare('INSERT INTO referrals (user_id, used_code, used_from_user_id, created_at) VALUES (:uid, :code, :from, NOW())');
            $insRef->execute([':uid' => $userId, ':code' => $referralInput, ':from' => $referredBy]);
        }

        $pdo->commit();

        $_SESSION['user'] = [
            'id' => $userId,
            'name' => $name,
            'phone' => $phone,
            'email' => $email ?: null,
            'username' => $username,
            'referral_code' => $refCode,
            'is_admin' => 0,
        ];

        Response::redirect('/dashboard');
    }

    public function login(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            http_response_code(400);
            echo 'Invalid CSRF token';
            return;
        }

        $identity = trim($_POST['identity'] ?? '');
        $password = $_POST['password'] ?? '';

        $pdo = Database::pdo();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE phone = :id OR email = :id OR username = :id LIMIT 1');
        $stmt->execute([':id' => $identity]);
        $user = $stmt->fetch();
        if (!$user || !password_verify($password, $user['password_hash'])) {
            $errors = ['login' => 'Invalid credentials'];
            $this->render('login', compact('errors', 'identity'));
            return;
        }

        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'name' => $user['name'],
            'phone' => $user['phone'],
            'email' => $user['email'],
            'username' => $user['username'],
            'referral_code' => $user['referral_code'],
            'is_admin' => (int)$user['is_admin'],
        ];

        Response::redirect('/dashboard');
    }

    public function logout(): void
    {
        session_destroy();
        Response::redirect('/');
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

