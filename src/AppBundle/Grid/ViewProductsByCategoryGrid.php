<?php

namespace AppBundle\Grid;

use APY\DataGridBundle\Grid\Column\Column;
use APY\DataGridBundle\Grid\Grid;

class ViewProductsByCategoryGrid implements ViewProductsByCategoryGridInterface
{
    public function viewProductsByCategory(Grid $grid): Grid
    {
        $grid->setHiddenColumns(['id', 'createdAt', 'updatedAt']);
        $grid->getColumn('title')->setTitle('Product Title')->setOperators([Column::OPERATOR_SLIKE])->setSize(5);
        $grid->getColumn('description')->setTitle('Description')->setOperators([Column::OPERATOR_SLIKE])->setSize(15);
        $grid->getColumn('price')->setTitle('Price')->setOperators([Column::OPERATOR_EQ])->setSize(5);
        $grid->getColumn('image')->setTitle('Product Image')->setOperators([])->setFilterable(false);
        $grid->getColumn('quantity')->setTitle('Quantity')->setOperators([Column::OPERATOR_EQ])->setSize(5);
        $grid->getColumn('outOfStock')->setTitle('Out Of Stock')->setValues([0 => 'No', 1 => 'Yes'])->setSize(5);

        $grid->getColumn('price')->manipulateRenderCell(function ($value, $row, $router) {
            /* @var $value  string */
            /* @var $row    \APY\DataGridBundle\Grid\Row */
            /* @var $router \Symfony\Bundle\FrameworkBundle\Routing\Router */
            return "$" . $value;
        });

        /* @var Column $column */
        foreach ($grid->getColumns() as $column) {
            $column->setAlign('center');
        }

        return $grid;
    }
}