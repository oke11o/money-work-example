<?php

namespace App\Tests\DataMapper;

use App\DataMapper\MapperRepository;
use App\DataMapper\TransactionMapper;
use App\DataMapper\UserMapper;
use App\Entity\Transaction;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class MapperRepositoryTest extends TestCase
{
    /**
     * @var MapperRepository
     */
    private $mapperRepository;

    public function setUp()
    {
        $list = [
            User::class,
            Transaction::class,
        ];
        $pdo = $this->prophesize(\PDO::class);
        $this->mapperRepository = new MapperRepository($pdo->reveal(), $list);
    }

    /**
     * @test
     * @dataProvider initExamples
     */
    public function init($entityClass, $mapperClass)
    {

        $mapper = $this->mapperRepository->getMapper($entityClass);
        $this->assertInstanceOf($mapperClass, $mapper);
    }

    public function initExamples()
    {
        return [
            [
                'entity' => User::class,
                'mapper' => UserMapper::class,
            ],
            [
                'entity' => Transaction::class,
                'mapper' => TransactionMapper::class,
            ],
        ];
    }

    /**
     * @test
     * @expectedException \App\Exception\DataMapper\MapperNotFoundException
     * @expectedExceptionMessage DataMapper for class "asdf" not found
     */
    public function invalidEntity()
    {
        $this->mapperRepository->getMapper('asdf');
    }
}
