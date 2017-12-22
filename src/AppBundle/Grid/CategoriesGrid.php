<?php

namespace AppBundle\Grid;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\Column;
use APY\DataGridBundle\Grid\Grid;

class CategoriesGrid implements CategoriesGridInterface
{
    /**
     * @param Grid $grid
     * @return Grid
     */
    public function configureCategoriesGrid(Grid $grid): Grid
    {
        $grid->setHiddenColumns(['id']);

        $deleteCategoryAction = new RowAction('Delete', 'deleteCategory');
        $deleteCategoryAction->setRouteParametersMapping(['id' => $grid->getColumn('id')->getId()]);

        $grid->getColumn('name')->setTitle('Category')
                                        ->setFilterable(false)
                                        ->setOperators([])
                                        ->setAlign(Column::ALIGN_CENTER)
                                        ->setSize(1);

        $grid->addRowAction($deleteCategoryAction);

        return $grid;
    }
}