<?php

namespace AppBundle\Repository;

interface IProductRepository extends IShoppingCartFindBuilder
{

    /**
     * @param string $name
     * @return array
     */
    public function getProductsByCategory(string $name): array;
}