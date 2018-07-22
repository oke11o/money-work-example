<?php

namespace App\DataMapper;

use PDO;

/**
 * Class AbstractMapper
 * @package App\DataMapper
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class AbstractMapper implements MapperInterface
{
    /**
     * @var PDO
     */
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

}