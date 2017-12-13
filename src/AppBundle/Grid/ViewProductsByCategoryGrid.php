<?php

namespace AppBundle\Grid;

use APY\DataGridBundle\Grid\Column\Column;
use APY\DataGridBundle\Grid\Grid;

class ViewProductsByCategoryGrid implements ViewProductsByCategoryGridInterface
{
    public function viewProductsByCategory(Grid $grid): Grid
    {
        $grid->setHiddenColumns(['id']);

//        $grid->getColumn('image')->manipulateRenderCell(function ($value, $row, $router) {
//            /* @var $value  Column */
//            /* @var $row    \APY\DataGridBundle\Grid\Row */
//            /* @var $router \Symfony\Bundle\FrameworkBundle\Routing\Router */
//        });

        return $grid;
    }
}