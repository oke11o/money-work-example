<?php

namespace App\DataMapper;

use App\Exception\DataMapper\MapperNotFoundException;
use DI\Annotation\Injectable;

/**
 * Class MapperRepository
 * @package App\DataMapper
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 *
 * @Injectable(lazy=true)
 */
class MapperRepository
{
    /**
     * @var \PDO
     */
    private $pdo;
    /**
     * @var MapperInterface[]
     */
    private $list = [];

    public function __construct(\PDO $pdo, array $list = [])
    {
        $this->pdo = $pdo;
        $this->init($list);
    }

    /**
     * @param string $class
     * @return MapperInterface
     *
     * @throws MapperNotFoundException
     */
    public function getMapper(string $class): MapperInterface
    {
        if (\array_key_exists($class, $this->list)) {
            return $this->list[$class];
        }

        throw new MapperNotFoundException($class);
    }

    /**
     * @param string[] $list
     */
    private function init(array $list)
    {
        foreach ($list as $class) {
            $namespace = explode('\\', $class);
            $className = array_pop($namespace);
            $directory = array_pop($namespace);
            $mapperClass = implode('\\', $namespace).'\\DataMapper\\'.$className.'Mapper';
            if (!class_exists($mapperClass)) {
                throw new MapperNotFoundException($mapperClass);
            }

            $this->list[$class] = new $mapperClass($this->pdo);
        }
    }
}