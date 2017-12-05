<?php

namespace AppBundle\Services;

use AppBundle\Entity\OrderedProducts;
use AppBundle\Entity\Product;
use FOS\UserBundle\Model\UserInterface;

interface IOrderedProductsService
{
    /**
     * @param UserInterface $user
     * @param Product $product
     * @return bool
     */
    public function addOrderedProduct(UserInterface $user, Product $product) : bool;

    /**
     * @param UserInterface $user
     * @param Product $product
     * @return bool
     */
    public function removeOrderedProduct(UserInterface $user, Product $product) : bool;

    /**
     * @param UserInterface $user
     * @param Product $product
     * @return bool
     */
    public function updateOrderProduct(UserInterface $user, Product $product) : bool;

    /**
     * @param int $id
     * @return OrderedProducts[]
     */
    public function getOrdersByUser(int $id): array;

    /**
     * @return float
     */
    public function getCheckoutFromAllProducts(): float;

    /**
     * @param int $id
     * @return mixed
     */
    public function getOrderedProductByID(int $id);
}