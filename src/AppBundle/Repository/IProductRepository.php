<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Categories;
use AppBundle\Entity\OrderedProducts;
use AppBundle\Entity\Product;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\User;
use Doctrine\ORM\QueryBuilder;

interface IProductRepository
{

    /**
     * @param string $name
     * @return array
     */
    public function getProductsByCategory(string $name): array;

    /**
     * @param int $id
     * @return mixed
     */
    public function findProductByID(int $id);

    /**
     * @return Product[]
     */
    public function getAllActiveProducts(): array;

    /**
     * @param Product $product
     * @param User $user
     * @return bool
     */
    public function updateProduct(Product $product, User $user): bool;

    /**
     * @param int $categoryID
     * @return array
     */
    public function getProductsByCategoryOnArray(int $categoryID): array;

    /**
     * @param Promotion $promotion
     * @return array
     */
    public function getProductsByPromotion(Promotion $promotion): array;

    /**
     * @param Promotion $promotion
     * @return array
     */
    public function getNonExistingProductsInPromotion(Promotion $promotion): array;

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
     * @param User $user
     * @param int $quantity
     * @return bool
     */
    public function markAsOutOfStock(OrderedProducts $orderedProducts,
                                     Product $product,
                                     User $user,
                                     int $quantity): bool;

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
}