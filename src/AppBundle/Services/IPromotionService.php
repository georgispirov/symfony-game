<?php

namespace AppBundle\Services;

use AppBundle\Entity\Categories;
use AppBundle\Entity\Product;
use AppBundle\Entity\Promotion;
use Symfony\Component\Form\FormInterface;

interface IPromotionService
{
    /**
     * @param Promotion $promotion
     * @param Categories $category
     * @param array $products
     * @param bool $isActive
     * @return bool
     */
    public function applyPromotionForCategory(Promotion $promotion,
                                              Categories $category,
                                              array $products,
                                              bool $isActive) : bool;

    /**
     * @param Promotion $promotion
     * @param Categories $category
     * @return bool
     */
    public function removePromotionForCategory(Promotion $promotion,
                                               Categories $category) : bool;

    /**
     * @param Promotion $promotion
     * @param Product[] $products
     * @return bool
     */
    public function applyPromotionForProducts(Promotion $promotion,
                                              array $products) : bool;

    /**
     * @param Promotion $promotion
     * @param array $products
     * @return bool
     */
    public function removePromotionForProducts(Promotion $promotion,
                                               array $products) : bool;


    /**
     * @return array
     */
    public function getAllActivePromotions(): array;

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

    /**
     * @param Promotion $promotion
     * @return array
     */
    public function getNonExistingProductsInPromotion(Promotion $promotion): array;

    /**
     * @param int $promotionID
     * @return null|Promotion
     */
    public function getPromotionByID(int $promotionID): Promotion;

    /**
     * @param Promotion $promotion
     * @param Product[] $products
     * @return bool
     */
    public function applyProductsOnExistingPromotion(Promotion $promotion, array $products): bool;

    /**
     * @param array $data
     * @return Product[]
     */
    public function collectRequestProducts(array $data): array;

    /**
     * @param Promotion $promotion
     * @return bool
     */
    public function updatePromotion(Promotion $promotion): bool;

    /**
     * @param FormInterface $form
     * @return FormInterface
     */
    public function configurePromotionUpdateForm(FormInterface $form): FormInterface;
}