<?php

namespace App\Exception\DataMapper;

/**
 * Class RecordNotFoundException
 * @package App\Exception\DataMapper
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class RecordNotFoundException extends DataMapperException
{
    public function __construct(int $id, string $table)
    {
        parent::__construct(sprintf('Not found record for id=%d in table="%s"', $id, $table));
    }

}