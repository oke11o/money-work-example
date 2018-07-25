<?php

namespace App\DataMapper;

use App\Entity\User;
use App\Exception\DataMapper\RecordNotFoundException;
use Money\Currency;
use Money\Money;

/**
 * Class UserMapper
 * @package App\DataMapper
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class UserMapper extends AbstractMapper
{
    private const TABLE = 'user';

    private const FIELD_ID = 'id';
    private const FIELD_EMAIL = 'email';
    private const FIELD_PASSWORD = 'password';
    private const FIELD_AMOUNT = 'amount';
    private const FIELD_CURRENCY = 'currency';

    /**
     * @param int $id
     * @return User|null
     */
    public function find(int $id): ?User
    {
        $table = self::TABLE;
        $fieldId = self::FIELD_ID;

        $sql = "SELECT * FROM {$table} WHERE {$fieldId}=:id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(self::FIELD_ID, $id);
        $statement->execute();

        $data = $statement->fetch();
        if (!$data) {
            return null;
        }

        return $this->map($data);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findOneByEmail(string $email): ?User
    {
        $table = self::TABLE;
        $fieldEmail = self::FIELD_EMAIL;
        $sql = "SELECT * FROM {$table} WHERE {$fieldEmail}=:email";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(self::FIELD_EMAIL, $email);
        $statement->execute();

        $data = $statement->fetch();
        if (!$data) {
            return null;
        }

        return $this->map($data);
    }

    /**
     * @param User $user
     * @param Money $money
     * @return null|string
     */
    public function withdraw(User $user, Money $money): ?string
    {
        $this->begin();
        $error = null;
        try {
            $currentAmount = $this->getAmountWithBlockForUpdate($user);
            if ($currentAmount->greaterThanOrEqual($money)) {
                $this->doWithdraw($user, $money);
                $user->setAmount($user->getAmount()->subtract($money));
            } else {
                $error = 'Not enouth money';
            }
        } catch (\Exception $exception) {
            $error = $exception->getMessage();
        }
        $this->commit();

        return $error;
    }

    /**
     * @param array $data
     * @return User
     */
    private function map(array $data): User
    {
        return (new User())
            ->setId($data[self::FIELD_ID])
            ->setEmail($data[self::FIELD_EMAIL])
            ->setPassword($data[self::FIELD_PASSWORD])
            ->setAmount(new Money($data[self::FIELD_AMOUNT], new Currency($data[self::FIELD_CURRENCY])));
    }

    private function begin()
    {
        $sql = 'BEGIN;';
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
    }

    private function commit()
    {
        $sql = 'COMMIT;';
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
    }

    /**
     * @param User $user
     * @return Money
     * @throws \App\Exception\DataMapper\RecordNotFoundException
     */
    private function getAmountWithBlockForUpdate(User $user): Money
    {
        $table = self::TABLE;
        $fieldId = self::FIELD_ID;
        $fieldAmount = self::FIELD_AMOUNT;
        $fieldCurrency = self::FIELD_CURRENCY;

        $sql = "SELECT {$fieldAmount}, {$fieldCurrency} FROM {$table} WHERE {$fieldId}=:id FOR UPDATE;";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':id', $user->getId());
        $statement->execute();

        $data = $statement->fetch();
        if (!$data) {
            throw new RecordNotFoundException($user->getId(), self::TABLE);
        }

        return new Money($data[self::FIELD_AMOUNT], new Currency($data[self::FIELD_CURRENCY]));
    }

    private function doWithdraw(User $user, Money $money)
    {
        $table = self::TABLE;
        $amount = $money->getAmount();
        $id = $user->getId();

        $sql = "UPDATE {$table} SET amount=amount-:donate WHERE id=:id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':donate', $amount);
        $statement->bindParam(':id', $id);
        $statement->execute();
    }
}