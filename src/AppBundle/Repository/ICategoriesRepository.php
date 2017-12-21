<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Categories;
use AppBundle\Entity\Product;

interface ICategoriesRepository
{
    /**
     * @param string $name
     * @return mixed
     */
    public function findByCategoryName(string $name);

    /**
     * @param Categories $categories
     * @return bool
     */
    public function addCategory(Categories $categories): bool;

    /**
     * @return array
     */
    public function getAllCategoriesOnArray(): array;

    /**
     * @param Categories $categories
     * @return bool
     */
    public function removeCategoryWithoutProducts(Categories $categories): bool;

    /**
     * @param Categories $categories
     * @param Product[] $product
     * @return bool
     */
    public function removeCategoryWithProducts(Categories $categories, array$product): bool;
}