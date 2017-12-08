<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Product;

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
}