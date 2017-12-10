<?php

namespace AppBundle\Services;

use AppBundle\Entity\Product;
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
}