<?php

namespace App\DataMapper;

use App\Entity\User;
use Money\Money;

/**
 * Class UserMapper
 * @package App\DataMapper
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class UserMapper extends AbstractMapper
{
    public const TABLE = 'user';

    public function find(int $id): ?User
    {
        $table = self::TABLE;
        $sql = "SELECT * FROM {$table} WHERE id=:id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam('id', $id);
        $statement->execute();

        $data = $statement->fetch();
        if (!$data) {
            return null;
        }

        return $this->map($data);
    }

    public function findOneByEmail(string $email): ?User
    {
        $table = self::TABLE;
        $sql = "SELECT * FROM {$table} WHERE email=:email";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam("email", $email);
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
     */
    public function withdraw(User $user, Money $money)
    {
        $table = self::TABLE;
        $sql = "UPDATE {$table} SET amount=amount-:donate WHERE id=:id";
        $statement = $this->pdo->prepare($sql);
        $amount = $money->getAmount();
        $statement->bindParam(':donate', $amount);
        $id = $user->getId();
        $statement->bindParam(':id', $id);
        $statement->execute();

    }

    /**
     * @param array $data
     * @return User
     */
    private function map(array $data): User
    {
        return (new User())
            ->setId($data['id'])
            ->setEmail($data['email'])
            ->setPassword($data['password'])
            ->setAmount(Money::RUB($data['amount']));
    }
}