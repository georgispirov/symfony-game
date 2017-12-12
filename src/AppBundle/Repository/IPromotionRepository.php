<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Promotion;
use Doctrine\ORM\QueryBuilder;

interface IPromotionRepository
{
    /**
     * @return array
     */
    public function getActivePromotions() : array;

    /**
     * @return array
     */
    public function getAllPromotions() : array;

    /**
     * @return null|Promotion
     */
    public function getPromotionByInterval();

    /**
     * @param Promotion $promotion
     * @return bool
     */
    public function addPromotionForProducts(Promotion $promotion): bool;
}