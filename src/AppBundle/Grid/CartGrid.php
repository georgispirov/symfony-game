<?php

namespace AppBundle\Grid;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\Column;
use APY\DataGridBundle\Grid\Grid;
use APY\DataGridBundle\Grid\Exception\InvalidArgumentException as APYInvalidArgumentException;

class CartGrid implements CartGridInterface
{
    /**
     * @param Grid $grid
     * @return Grid
     */
    public function orderedProductsDataGrid(Grid $grid): Grid
    {
        if ( !$grid instanceof Grid ) {
            throw new APYInvalidArgumentException('Applied argument must be instance of Grid!');
        }

        $grid->setHiddenColumns(['orderedProductID', 'viewProductID']);
        $grid->setLimits([5,5,5]);

        $productColumn = new RowAction('View', 'viewProduct');
        $productColumn->setRouteParametersMapping(['viewProductID' => $grid->getColumn('viewProductID')]);

        $updateColumn = new RowAction('Update', 'updateOrderedProduct');
        $updateColumn->setRouteParametersMapping(['productID' => $grid->getColumn('orderedProductID')->getId()]);

        $deleteColumn = new RowAction('Delete', 'removeOrderedProduct');
        $deleteColumn->setRouteParametersMapping(['productID' => $grid->getColumn('orderedProductID')->getId()]);

        $grid->getColumn('orderedDate')
            ->setTitle('Ordered Date')
            ->setOperators(['slike']);

        $grid->getColumn('User')
            ->setOperators(['slike']);

        $grid->getColumn('Confirmed')
            ->setValues([0 => 'No', 1 => 'Yes']);

        $grid->getColumn('Confirmed')->manipulateRenderCell(
            function ($value, $row, $router) {
                return (bool) $value;
            }
        );

        $grid->getColumn('Price')
            ->manipulateRenderCell(function ($value, $row, $router) {
                /* @var $value  integer */
                /* @var $row    \APY\DataGridBundle\Grid\Row */
                /* @var $router \Symfony\Bundle\FrameworkBundle\Routing\Router */
                return '$' . $value;
            });

        $grid->getColumn('Quantity')
            ->setFilterable(true)
            ->setFilterType('input')
            ->setOperators(['eq']);

        $grid->getColumn('Quantity')->manipulateRenderCell(
            function ($value, $row, $router) {
                return intval($value);
            }
        );

        $grid->getColumn('Price')->setOperators(['eq']);

        $grid->addRowAction($productColumn);
        $grid->addRowAction($updateColumn);
        $grid->addRowAction($deleteColumn);

        /* @var Column $column */
        foreach ($grid->getColumns() as $column) {
            $column->setAlign('center');
        }

        return $grid;
    }
}