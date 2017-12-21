<?php

namespace AppBundle\Grid;

use APY\DataGridBundle\Grid\Grid;

class SellBoughtProductsGrid implements SellBoughtProductsGridInterface
{
    /**
     * @param Grid $grid
     * @return Grid
     */
    public function configureSellBoughtGrid(Grid $grid): Grid
    {
        $grid->setHiddenColumns(['productID']);

        $grid->getColumn('orderedDate')->setTitle('Ordered Date')->setOperators([])->setFilterable(false);
        $grid->getColumn('confirmed')->setTitle('Confirmed Orders');

        $grid->getColumn('Quantity')->manipulateRenderCell(function ($value, $row, $router) {
            /* @var $value  int */
            /* @var $row    \APY\DataGridBundle\Grid\Row */
            /* @var $router \Symfony\Bundle\FrameworkBundle\Routing\Router */
            return (int) $value;
        })->setOperators([])->setFilterable(false);

        return $grid;
    }
}