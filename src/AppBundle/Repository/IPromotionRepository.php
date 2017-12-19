<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Categories;
use AppBundle\Entity\Product;
use AppBundle\Entity\Promotion;

interface IPromotionRepository
{
    /**
     * @return array
     */
    public function getActivePromotions(): array;

    /**
     * @return array
     */
    public function getAllPromotions(): array;

    /**
     * @return null|Promotion
     */
    public function getPromotionByInterval();

    /**
     * @param Promotion $promotion
     * @return bool
     */
    public function addPromotionForProducts(Promotion $promotion): bool;

    /**
     * @param int $promotionID
     * @return null|Promotion
     */
    public function getPromotionByID(int $promotionID);

    /**
     * @param Promotion $promotion
     * @param Product[] $products
     * @return bool
     */
    public function removePromotionFromProducts(Promotion $promotion,
                                                array $products): bool;

    /**
     * @param Promotion $promotion
     * @param Product[] $products
     * @return bool
     */
    public function applyExistingPromotionOnProducts(Promotion $promotion,
                                                     array $products): bool;

    /**
     * @param Promotion $promotion
     * @param Categories $categories
     * @param Product[] $products
     * @return bool
     */
    public function applyPromotionOnCategory(Promotion $promotion,
                                             Categories $categories, array $products): bool;

    public function removePromotionWithoutProducts(Promotion $promotion): bool;
}