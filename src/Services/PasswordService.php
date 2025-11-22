<?php
// src/Services\PasswordService.php

namespace Cineplanet\App\Services;

class PasswordService
{
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
}