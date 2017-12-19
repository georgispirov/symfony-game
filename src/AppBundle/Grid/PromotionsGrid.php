<?php

namespace AppBundle\Grid;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\Column;
use APY\DataGridBundle\Grid\Grid;
use APY\DataGridBundle\Grid\Row;

class PromotionsGrid implements PromotionsGridInterface
{
    /**
     * @param Grid $grid
     * @return Grid
     */
    public function promotionsDataGrid(Grid $grid): Grid
    {
        $grid->setHiddenColumns(['id']);

        $viewProductsInPromotion = new RowAction('View Products', 'productsByActivePromotion');
        $viewProductsInPromotion->setRouteParametersMapping(['promotionID' => $grid->getColumn('id')->getId()]);

        $removePromotionAction   = new RowAction('Remove Promotion', 'removePromotion');
        $removePromotionAction->setRouteParametersMapping(['promotionID' => $grid->getColumn('id')->getId()]);

        $addProductsToPromotionAction = new RowAction('Add Products', 'productsOnExistingPromotion');
        $addProductsToPromotionAction->setRouteParametersMapping(['promotionID' => $grid->getColumn('id')->getId()]);

        $updatePromotionAction = new RowAction('Update Promotion', 'editPromotion');
        $updatePromotionAction->setRouteParametersMapping(['promotionID' => $grid->getColumn('id')->getId()]);

        $grid->getColumn('discount')->setTitle('Discount')->setOperators([Column::OPERATOR_EQ])->setSize(10);
        $grid->getColumn('startDate')->setTitle('Start Date')->setOperators([Column::OPERATOR_EQ])->setSize(10);
        $grid->getColumn('endDate')->setTitle('End Date')->setOperators([Column::OPERATOR_EQ])->setSize(10);
        $grid->getColumn('isActive')->setTitle('Active')->setValues(['0' => 'No', '1' => 'Yes'])->setSize(5)->setSeparator(10);

        /* @var Column $column */
        foreach ($grid->getColumns() as $column) {
            $column->setAlign(Column::ALIGN_CENTER);
        }

        $grid->getColumn('discount')->manipulateRenderCell(
            function ($value, $row, $router) {
                return $value . "%";
            }
        );

        $grid->getColumn('isActive')->manipulateRenderCell(
            function ($value, $row, $router) {
                return (bool) $value;
            }
        );

        $grid->addRowAction($addProductsToPromotionAction);
        $grid->addRowAction($removePromotionAction);
        $grid->addRowAction($updatePromotionAction);
        $grid->addRowAction($viewProductsInPromotion);

        return $grid;
    }
}