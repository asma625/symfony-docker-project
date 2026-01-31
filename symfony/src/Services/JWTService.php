<?php

namespace App\Services;

use App\Utils\Base64url;
use DateTimeImmutable;
use Doctrine\ORM\Query\Expr\Base;

class JWTService
{
    public function __construct(private int $validity = 3600) {}

    // On génère le token JWT
    public function generate(array $header, array $payload, string $secret): string
    {
        $base64Header = $this->encodeHeader($header);
        $base64Payload = $this->encodePayload($payload, $this->validity);
        $base64Signature = $this->sign($base64Header, $base64Payload, $secret);
        return $this->generateToken($base64Header, $base64Payload, $base64Signature);
    }

    public function encodeHeader(array $header): string
    {
        $jsonHeader = json_encode($header);
        $base64Header = Base64url::encode($jsonHeader);
        return $base64Header;
    }

    public function encodePayload(array $payload, int $validity): string
    {
        if ($validity > 0) {
            $now = new DateTimeImmutable();
            $exp = $now->getTimestamp() + $validity;

            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $exp;
            $payload['iss'] = 'mon-app';
        }
        $jsonPayload = json_encode($payload);
        $base64Payload = Base64url::encode($jsonPayload);
        return $base64Payload;
    }
    public function sign(string $base64Header, string $base64Payload, string $secret): string
    {
        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $secret, true);
        $base64Signature = Base64url::encode($signature);
        return $base64Signature;
    }

    public function generateToken(string $base64Header, string $base64Payload, string $base64Signature): string
    {
        $jwt = $base64Header . '.' . $base64Payload . '.' . $base64Signature;
        return $jwt;
    }


    //On vérifie que le token est valide (correctement formé)
    public function isValid(string $token): bool
    {
        return preg_match(
             '/^[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+$/',
            $token
        ) === 1;
    }

    // On récupère le Payload
    public function getPayload(string $token): array
    {
        // On démonte le token
        $array = explode('.', $token);

        // On décode le Payload
        $payload = json_decode(Base64url::decode($array[1]), true);

        return $payload;
    }

    // On récupère le Header
    public function getHeader(string $token): array
    {
        // On démonte le token
        $array = explode('.', $token);

        // On décode le Header
        $header = json_decode(Base64url::decode($array[0]), true);

        return $header;
    }

    // On vérifie si le token a expiré
    public function isExpired(string $token): bool
    {

        $payload = $this->getPayload($token);

        $now = new DateTimeImmutable();

        return $payload['exp'] < $now->getTimestamp();
    }

    // On vérifie la signature du Token jamais le on verfier le token sera jamais le meme apres chaque génération
    public function check(string $token, string $secret)
    {
        if (!$this->isValid($token)) {
            return false;
        }
        $tokenParts = explode('.', $token);
        $header = $tokenParts[0];
        $payload = $tokenParts[1];
        if (!isset($tokenParts[2])) {
            return false;
        }
        if ($this->getHeader($token)['alg'] !== 'HS256') {
            return false;
        }

        $signature = $this->sign($header, $payload, $secret);

        return  hash_equals($tokenParts[2], $signature);
    }
}
