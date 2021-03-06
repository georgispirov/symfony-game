<?php

namespace AppBundle\Services;

use AppBundle\Entity\Categories;
use AppBundle\Entity\OrderedProducts;
use AppBundle\Entity\Product;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\User;

interface IProductService
{
    /**
     * @return array
     */
    public function getAll(): array;

    /**
     * @param string $categoryName
     * @return Product[]
     */
    public function getProductsByCategory(string $categoryName): array;

    /**
     * @param int $id
     * @return mixed
     */
    public function getProductByID(int $id);

    /**
     * @param Product $product
     * @param User $user
     * @return bool
     */
    public function updateProduct(Product $product, User $user): bool;

    /**
     * @param int $categoryID
     * @return array
     * @internal param string $categoryName
     */
    public function getProductsByCategoryOnArray(int $categoryID): array;

    /**
     * @param Promotion $promotion
     * @return array
     */
    public function getProductsByPromotion(Promotion $promotion): array;

    /**
     * @param int $productID
     * @return array
     */
    public function getProductByIDOnArray(int $productID): array;

    /**
     * @param string $title
     * @return null|Product
     */
    public function getProductByTitle(string $title);

    /**
     * @param OrderedProducts $orderedProducts
     * @param Product $product
     * @param int $quantity
     * @return bool
     */
    public function markAsOutOfStock(OrderedProducts $orderedProducts,
                                     Product $product, int $quantity): bool;

    /**
     * @param OrderedProducts $orderedProducts
     * @param Product $product
     * @param User $user
     * @param int $quantity
     * @return bool
     */
    public function decreaseQuantityOnProduct(OrderedProducts $orderedProducts,
                                              Product $product,
                                              User $user,
                                              int $quantity): bool;

    /**
     * @param Promotion $promotion
     * @return array
     */
    public function getProductsByPromotionOnObjects(Promotion $promotion): array;

    /**
     * @param Product $product
     * @param Promotion $promotion
     * @return bool
     */
    public function removeProductFromPromotion(Product $product, Promotion $promotion): bool;

    /**
     * @param Categories $categories
     * @return array
     */
    public function getAllNonActiveAndOutOfStockProductsByCategory(Categories $categories): array;

    /**
     * @param Product $product
     * @return bool
     */
    public function deleteProduct(Product $product): bool;
}