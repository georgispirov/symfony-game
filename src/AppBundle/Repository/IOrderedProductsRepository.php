<?php

namespace AppBundle\Repository;

use AppBundle\Entity\OrderedProducts;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;

interface IOrderedProductsRepository extends IShoppingCartFindBuilder
{
    /**
     * @param int $id
     * @return OrderedProducts[]
     */
    public function getOrderedProductsByUser(int $id): array;

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
}