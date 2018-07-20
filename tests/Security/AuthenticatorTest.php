<?php

namespace App\Tests\Security;

use App\Provider\UserProviderInterface;
use App\Security\Authenticator;
use App\Security\PasswordEncoder;
use PHPUnit\Framework\TestCase;
use App\Entity\User;
use Prophecy\Prophecy\ObjectProphecy;

class AuthenticatorTest extends TestCase
{
    /**
     * @var UserProviderInterface|ObjectProphecy
     */
    private $userProvider;
    /**
     * @var PasswordEncoder|ObjectProphecy
     */
    private $encoder;
    /**
     * @var Authenticator
     */
    private $authenticator;

    public function setUp()
    {
        $this->userProvider = $this->prophesize(UserProviderInterface::class);
        $this->encoder = $this->prophesize(PasswordEncoder::class);

        $this->authenticator = new Authenticator($this->userProvider->reveal(), $this->encoder->reveal());
    }

    /**
     * @test
     * @expectedException \App\Exception\Security\UserNotFoundException
     */
    public function userNotFound()
    {
        $username = 'username';
        $password = 'password';

        $this->userProvider->findByUsername($username)->shouldBeCalled()->willReturn(null);

        $this->authenticator->authenticate($username, $password);
    }

    /**
     * @test
     * @expectedException \App\Exception\Security\InvalidPasswordException
     */
    public function invalidPassword()
    {
        $username = 'username';
        $userPassHash = 'userPass';
        $formPass = 'formPass';
        $user = (new User())->setEmail($username)->setPassword($userPassHash);

        $this->userProvider->findByUsername($username)->shouldBeCalled()->willReturn($user);
        $this->encoder->verifyPassword($formPass, $userPassHash)->shouldBeCalled()->willReturn(false);

        $this->authenticator->authenticate($username, $formPass);
    }

    /**
     * @test
     */
    public function authenticate()
    {
        $username = 'username';
        $userPassHash = 'userPass';
        $formPass = 'formPass';
        $user = (new User())->setEmail($username)->setPassword($userPassHash);

        $this->userProvider->findByUsername($username)->shouldBeCalled()->willReturn($user);
        $this->encoder->verifyPassword($formPass, $userPassHash)->shouldBeCalled()->willReturn(true);

        $this->assertEquals($user, $this->authenticator->authenticate($username, $formPass));
    }
}
