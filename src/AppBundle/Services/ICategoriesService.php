<?php

namespace AppBundle\Services;

use AppBundle\Entity\Categories;
use AppBundle\Entity\Product;

interface ICategoriesService
{
    /**
     * @return array
     */
    public function getAllCategories() : array;

    /**
     * @param int $id
     * @return null|Categories
     */
    public function getCategoryByID(int $id);

    /**
     * @param string $categoryName
     * @return null|Categories
     */
    public function getCategoryByName(string $categoryName);

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