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
     * @return null|string
     */
    public function withdraw(User $user, Money $money): ?string
    {
        $this->begin();
        $error = null;
        try {
            $currentAmount = $this->checkForUpdate($user);
            /** @var Money $currentMoney */
            $currentMoney = Money::RUB($currentAmount);
            if ($currentMoney->greaterThanOrEqual($money)) {

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
            ->setId($data['id'])
            ->setEmail($data['email'])
            ->setPassword($data['password'])
            ->setAmount(Money::RUB($data['amount']));
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

    private function checkForUpdate(User $user)
    {
        $table = self::TABLE;
        $id = $user->getId();
        $sql = "SELECT amount FROM {$table} WHERE id=:id FOR UPDATE;";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':id', $id);
        $statement->execute();

        return $statement->fetchColumn();
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