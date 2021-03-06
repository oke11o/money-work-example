<?php

namespace App\Entity;

use App\Enum\AvailableCurrencyEnum;
use Money\Currency;
use Money\Money;

/**
 * Class User
 * @package App\Entity
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class User
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $email = '';

    /**
     * @var string
     */
    private $password = '';

    /**
     * @var Money
     */
    private $amount;

    public function __construct()
    {
        $this->amount = new Money(0, new Currency(AvailableCurrencyEnum::RUB));
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Money
     */
    public function getAmount(): Money
    {
        return $this->amount;
    }

    /**
     * @param Money $amount
     *
     * @return self
     */
    public function setAmount(Money $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
