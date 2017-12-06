<?php

namespace AppBundle\Services;

use AppBundle\Entity\OrderedProducts;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;

interface ICartService
{
    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function addProduct(User $user, Product $product) : bool;

    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function removeProduct(User $user, Product $product) : bool;

    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function updateProduct(User $user, Product $product): bool;

    /**
     * @param User $user
     * @return mixed
     */
    public function userCheckout(User $user);

    /**
     * @param array $products
     * @return float
     */
    public function getTotalOfProducts(array $products): float;

    /**
     * @param User $user
     * @return OrderedProducts[]
     */
    public function getOrderedProductByUser(User $user): array;

    /**
     * @param int $id
     * @return mixed
     */
    public function getOrderedProductByID(int $id);

    public function hasUserEnoughCash(float $itemPrice, float $userCash): bool;
}