<?php

namespace AppBundle\Services;

use AppBundle\Entity\Categories;
use AppBundle\Entity\Promotion;

interface IPromotionService
{
    /**
     * @param Promotion $promotion
     * @param Categories $category
     * @return bool
     */
    public function applyPromotionForCategory(Promotion $promotion,
                                              Categories $category) : bool;

    /**
     * @param Promotion $promotion
     * @param Categories $category
     * @return bool
     */
    public function removePromotionForCategory(Promotion $promotion,
                                               Categories $category) : bool;

    /**
     * @param Promotion $promotion
     * @return bool
     */
    public function applyPromotionForProducts(Promotion $promotion) : bool;

    /**
     * @param Promotion $promotion
     * @return bool
     */
    public function removePromotionForProducts(Promotion $promotion) : bool;
}