<?php

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;

interface ICommentsRepository
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