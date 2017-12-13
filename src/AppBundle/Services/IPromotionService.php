<?php

namespace AppBundle\Services;

use AppBundle\Entity\Categories;
use AppBundle\Entity\Product;
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

    /**
     * @return array
     */
    public function getAllPromotions(): array;

    /**
     * @return array
     */
    public function getAllActivePromotion(): array;

    /**
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getAllPromotionsByDateInterval(string $startDate, string $endDate): array;

    /**
     * @param Categories $category
     * @return array
     */
    public function getAllPromotionsByCategory(Categories $category): array;

    /**
     * @param Product $product
     * @return array
     */
    public function getAllPromotionsByProduct(Product $product): array;
}