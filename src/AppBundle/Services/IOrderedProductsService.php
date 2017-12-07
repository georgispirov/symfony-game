<?php

namespace AppBundle\Services;

use AppBundle\Entity\OrderedProducts;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;

interface IOrderedProductsService
{
    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function addOrderedProduct(User $user, Product $product) : bool;

    /**
     * @param OrderedProducts $product
     * @return bool
     */
    public function removeOrderedProduct(OrderedProducts $product) : bool;

    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function updateOrderProduct(User $user, Product $product) : bool;

    /**
     * @param User $user
     * @return array
     */
    public function getOrdersByUser(User $user): array;

    /**
     * @return float
     */
    public function getCheckoutFromAllProducts(): float;

    /**
     * @param int $id
     * @return mixed
     */
    public function getOrderedProductByID(int $id);

    /**
     * @param Product $product
     * @return mixed
     */
    public function getOrderedProductByProduct(Product $product);

    /**
     * @param OrderedProducts $orderedProduct
     * @param Product $product
     * @return bool
     */
    public function increaseQuantity(OrderedProducts $orderedProduct,
                                     Product $product): bool;

    /**
     * @param OrderedProducts $orderedProducts
     * @param Product $product
     * @return bool
     */
    public function decreaseQuantityOnOrderedProduct(OrderedProducts $orderedProducts,
        Product $product): bool;

    /**
     * @param User $user
     * @param Product $product
     * @return mixed
     * @internal param int $id
     */
    public function getOrderByUserAndProduct(User $user, Product $product);
}