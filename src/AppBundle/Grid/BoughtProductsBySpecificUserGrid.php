<?php

namespace AppBundle\Grid;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\Column;
use APY\DataGridBundle\Grid\Grid;

class BoughtProductsBySpecificUserGrid implements BoughtProductsBySpecificUserGridInterface
{
    public function configureBoughtProductsBySpecificUser(Grid $grid): Grid
    {
        $sellBoughtProductAction = new RowAction('Update Bought Product', 'updateBoughtProductOnUser');
        $sellBoughtProductAction->setRouteParametersMapping(['orderedProductID']);

        $deleteBoughtProducts    = new RowAction('Delete Bought Product', 'deleteBoughtProductOnUser');
        $deleteBoughtProducts->setRouteParametersMapping(['orderedProductID']);

        $grid->setHiddenColumns(['productID', 'orderedProductID', 'Quantity', 'Seller']);

        $grid->getColumn('orderedDate')->setTitle('Ordered Date')->setOperators([])->setFilterable(false);
        $grid->getColumn('confirmed')->setTitle('Confirmed Orders')->setSize(1);
        $grid->getColumn('confirmed')->manipulateRenderCell(function ($value, $row, $router) {
            return (int) $value;
        })->setOperators([])->setFilterable(false);

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
        $grid->addRowAction($deleteBoughtProducts);

        return $grid;
    }
}