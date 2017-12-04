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
     * @return object
     */
    public function getCategoryByID(int $id) : object;

    /**
     * @param string $categoryName
     * @return null|Categories
     */
    public function getCategoryByName(string $categoryName);
}