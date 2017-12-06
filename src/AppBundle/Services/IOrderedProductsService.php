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
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function removeOrderedProduct(User $user, Product $product) : bool;

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
     * @return OrderedProducts[]
     */
    public function getOrderedProductByID(int $id);
}