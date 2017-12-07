<?php

namespace AppBundle\Repository;

interface ICategoriesRepository
{
    /**
     * @param string $name
     * @return mixed
     */
    public function findByCategoryName(string $name);
}