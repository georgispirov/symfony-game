<?php

namespace AppBundle\Repository;

use AppBundle\Entity\OrderedProducts;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;

interface IOrderedProductsRepository
{
    /**
     * @param User $user
     * @return OrderedProducts[]
     */
    public function getOrderedProductsByUser(User $user): array;

    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function addOrderedProduct(User $user, Product $product): bool;

    /**
     * @param int $id
     * @return mixed
     */
    public function findOrderedProductByID(int $id);

    /**
     * @param OrderedProducts $product
     * @return bool
     */
    public function removeOrderedProduct(OrderedProducts $product): bool;

    /**
     * @param OrderedProducts $orderedProduct
     * @return bool
     */
    public function increaseQuantity(OrderedProducts $orderedProduct): bool;
}