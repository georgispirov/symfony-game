<?php

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;

interface ICommentsRepository extends IShoppingCartFindBuilder
{
    /**
     * @return QueryBuilder
     */
    public function getCommentsOnCertainUser() : QueryBuilder;

    /**
     * @return QueryBuilder
     */
    public function getCommentOnCertainProduct() : QueryBuilder;
}