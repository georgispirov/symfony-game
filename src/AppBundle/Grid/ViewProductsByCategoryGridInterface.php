<?php

namespace AppBundle\Grid;

use APY\DataGridBundle\Grid\Grid;

interface ViewProductsByCategoryGridInterface
{
    public function viewProductsByCategory(Grid $grid): Grid;
}