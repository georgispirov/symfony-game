<?php

namespace AppBundle\Services;

use Doctrine\ORM\QueryBuilder;

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
}