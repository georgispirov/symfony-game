<?php

namespace AppBundle\Grid;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\Column;
use APY\DataGridBundle\Grid\Grid;

class SellBoughtProductsGrid implements SellBoughtProductsGridInterface
{
    /**
     * @param Grid $grid
     * @return Grid
     */
    public function configureSellBoughtGrid(Grid $grid): Grid
    {
        $sellBoughtProductAction = new RowAction('Sell Product', 'sellBoughtProduct');
        $sellBoughtProductAction->setRouteParametersMapping(['orderedProductID']);

        $grid->setHiddenColumns(['productID']);

        $grid->getColumn('orderedDate')->setTitle('Ordered Date')->setOperators([])->setFilterable(false);
        $grid->getColumn('confirmed')->setTitle('Confirmed Orders')->setSize(1);

        $grid->getColumn('Quantity')->manipulateRenderCell(function ($value, $row, $router) {
            /* @var $value  int */
            /* @var $row    \APY\DataGridBundle\Grid\Row */
            /* @var $router \Symfony\Bundle\FrameworkBundle\Routing\Router */
            return (int) $value;
        })->setOperators([])
          ->setFilterable(false);

        /* @var Column $column */
        foreach ($grid->getColumns() as $column) {
            $column->setAlign(Column::ALIGN_CENTER);
        }

        $grid->addRowAction($sellBoughtProductAction);

        return $grid;
    }
}