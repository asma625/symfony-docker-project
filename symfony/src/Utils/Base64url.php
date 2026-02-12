<?php

namespace App\Utils;

use InvalidArgumentException;

class Base64url
{
    public static function encode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); //encode en base64url puis remplace les caractères invalides 
        //telque + et / par - et _ et supprime les =
    }

    public static function decode(string $data): string
    {
        $remainder = strlen($data) % 4;
        if (0 !== $remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        $decoded = base64_decode(strtr($data, '-_', '+/'), true);
        if (false === $decoded) {
            throw new InvalidArgumentException('Invalid data provided');
        }

        return $decoded;
    }
}
