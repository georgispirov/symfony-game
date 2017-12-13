<?php

namespace AppBundle\Grid;

use APY\DataGridBundle\Grid\Grid;

interface CartGridInterface
{
    /**
     * @param Grid $grid
     * @return Grid
     */
    public function orderedProductsDataGrid(Grid $grid): Grid;
}