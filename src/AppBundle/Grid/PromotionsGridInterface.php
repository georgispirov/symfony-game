<?php

namespace AppBundle\Grid;

use APY\DataGridBundle\Grid\Grid;

interface PromotionsGridInterface
{
    /**
     * @param Grid $grid
     * @return Grid
     */
    public function promotionsDataGrid(Grid $grid): Grid;
}