<?php

namespace AppBundle\Grid;

use APY\DataGridBundle\Grid\Grid;

interface CategoriesGridInterface
{
    /**
     * @param Grid $grid
     * @return Grid
     */
    public function configureCategoriesGrid(Grid $grid): Grid;
}