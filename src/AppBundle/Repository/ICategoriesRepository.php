<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Categories;

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
}