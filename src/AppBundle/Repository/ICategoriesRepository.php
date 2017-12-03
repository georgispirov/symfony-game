<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Categories;

interface ICategoriesRepository extends IShoppingCartFindBuilder
{
    /**
     * @param string $name
     * @return mixed
     */
    public function findByCategoryName(string $name);
}