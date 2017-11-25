<?php

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;

interface IShoppingCartFindBuilder
{
    public function invokeFindByBuilder() : QueryBuilder;
}