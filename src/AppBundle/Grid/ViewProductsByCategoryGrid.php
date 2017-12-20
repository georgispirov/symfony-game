<?php

namespace AppBundle\Grid;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\Column;
use APY\DataGridBundle\Grid\Grid;
use Symfony\Component\HttpFoundation\Request;

class ViewProductsByCategoryGrid implements ViewProductsByCategoryGridInterface
{
    public function viewProductsByCategory(Grid $grid, Request $request): Grid
    {
        $queryString = explode('=', $request->getQueryString());
        $promotionID = end($queryString);

        $removeProductFromPromotionAction = new RowAction('Remove From Promotion', 'removeProductsFromPromotion');
        $removeProductFromPromotionAction->setRouteParameters(['promotionID' => $promotionID, 'id']);

        $grid->setHiddenColumns(['id', 'createdAt', 'updatedAt']);
        $grid->getColumn('title')->setTitle('Product Title')->setOperators([Column::OPERATOR_SLIKE]);
        $grid->getColumn('description')->setTitle('Description')->setOperators([])->setFilterable(false);
        $grid->getColumn('price')->setTitle('Price')->setOperators([Column::OPERATOR_EQ])->setSize(5);
        $grid->getColumn('image')->setTitle('Product Image')->setOperators([])->setFilterable(false);
        $grid->getColumn('quantity')->setTitle('Quantity')->setOperators([])->setFilterable(false);
        $grid->getColumn('outOfStock')->setTitle('Out Of Stock')->setValues([0 => 'No', 1 => 'Yes']);

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

        $grid->addRowAction($removeProductFromPromotionAction);

        return $grid;
    }
}