<?php

namespace App\service;

class TokenManager
{
    public static function generateToken()
    {
        return bin2hex(random_bytes(32));
    }
}