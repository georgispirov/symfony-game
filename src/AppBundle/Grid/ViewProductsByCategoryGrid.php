<?php

namespace AppBundle\Grid;

use APY\DataGridBundle\Grid\Grid;

class ViewProductsByCategoryGrid implements ViewProductsByCategoryGridInterface
{
    public function viewProductsByCategory(Grid $grid): Grid
    {
        $grid->setHiddenColumns(['id']);

        return $grid;
    }
}