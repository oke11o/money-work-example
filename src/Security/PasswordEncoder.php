<?php

namespace App\Security;

use DI\Annotation\Injectable;

/**
 * Class PasswordEncoder
 * @package App\Security
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 *
 * @Injectable(lazy=true)
 */
class PasswordEncoder
{
    /**
     * @param string $raw
     * @return string
     */
    public function encodePassword(string $raw): string
    {
        $options = [
            'cost' => 10,
        ];

        return password_hash($raw, PASSWORD_BCRYPT, $options);
    }

    /**
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}