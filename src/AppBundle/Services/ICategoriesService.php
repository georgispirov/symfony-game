<?php

namespace AppBundle\Services;

use AppBundle\Entity\Categories;

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
}