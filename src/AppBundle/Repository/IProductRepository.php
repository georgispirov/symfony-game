<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;

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
}