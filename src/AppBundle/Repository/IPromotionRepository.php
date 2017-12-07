<?php

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;

interface IPromotionRepository
{
    /**
     * @return QueryBuilder
     */
    public function getActivePromotions() : QueryBuilder;

    /**
     * @return QueryBuilder
     */
    public function getAllPromotions() : QueryBuilder;

    /**
     * @return QueryBuilder
     */
    public function getPromotionByInterval() : QueryBuilder;
}