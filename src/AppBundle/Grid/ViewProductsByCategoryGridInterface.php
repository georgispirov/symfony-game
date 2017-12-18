<?php

namespace AppBundle\Grid;

use APY\DataGridBundle\Grid\Grid;
use Symfony\Component\HttpFoundation\Request;

interface ViewProductsByCategoryGridInterface
{
    /**
     * @param Grid $grid
     * @param Request $request
     * @return Grid
     */
    public function viewProductsByCategory(Grid $grid, Request $request): Grid;
}