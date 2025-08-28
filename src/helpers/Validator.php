<?php

namespace App\helpers;

class Validator
{
    public static function isValidPhone(string $phone): bool
    {
        return (bool)preg_match('/^[0-9]{7,15}$/', $phone);
    }

    public static function isValidUsername(string $username): bool
    {
        return (bool)preg_match('/^[a-zA-Z0-9_\.\-]{3,50}$/', $username);
    }

    public static function isValidPassword(string $password): bool
    {
        return (bool)preg_match('/^(?=.{6,})(?=.*[A-Za-z])(?=.*\d)(?=.*\W).+$/', $password);
    }

    public static function isValidEmail(?string $email): bool
    {
        if ($email === null || $email === '') return true;
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

