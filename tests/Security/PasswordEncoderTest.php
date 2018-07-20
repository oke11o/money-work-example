<?php

namespace App\Tests\Security;

use App\Security\PasswordEncoder;
use PHPUnit\Framework\TestCase;

class PasswordEncoderTest extends TestCase
{
    /**
     * @test
     */
    public function verifyPassword()
    {
        $encoder = new PasswordEncoder();

        $password = 'asdf';
        $hash = '$2y$10$99ry9IrnRrF2kyyZxEo4WOj9iQItbYIpqeuaalosYxTr.l10ueeva';

        $bool = $encoder->verifyPassword($password, $hash);

        $this->assertTrue($bool);
    }

    /**
     * @test
     */
    public function encodeAndViryfyPassword()
    {
        $encoder = new PasswordEncoder();

        $password = bin2hex(\random_bytes(10));
        $hash = $encoder->encodePassword($password);

        $bool = $encoder->verifyPassword($password, $hash);

        $this->assertTrue($bool);
    }
}
