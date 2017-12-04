<?php

namespace AppBundle\Services;

use AppBundle\Entity\Product;

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
}