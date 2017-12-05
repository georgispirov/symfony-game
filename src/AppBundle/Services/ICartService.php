<?php

namespace AppBundle\Services;

use AppBundle\Entity\OrderedProducts;
use AppBundle\Entity\Product;
use FOS\UserBundle\Model\UserInterface;

interface ICartService
{
    /**
     * @param UserInterface $user
     * @param Product $product
     * @return bool
     */
    public function addProduct(UserInterface $user, Product $product) : bool;

    /**
     * @param UserInterface $user
     * @param Product $product
     * @return bool
     */
    public function removeProduct(UserInterface $user, Product $product) : bool;

    /**
     * @param UserInterface $user
     * @param Product $product
     * @return bool
     */
    public function updateProduct(UserInterface $user, Product $product): bool;

    /**
     * @param UserInterface $user
     * @return mixed
     */
    public function userCheckout(UserInterface $user);

    /**
     * @param array $products
     * @return float
     */
    public function getTotalOfProducts(array $products): float;

    /**
     * @param int $id
     * @return OrderedProducts[]
     */
    public function getOrderedProductByUser(int $id): array;

    /**
     * @param int $id
     * @return mixed
     */
    public function getOrderedProductByID(int $id);
}