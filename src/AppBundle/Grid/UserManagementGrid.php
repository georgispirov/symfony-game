<?php

namespace AppBundle\Grid;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\Column;
use APY\DataGridBundle\Grid\Grid;

class UserManagementGrid implements UserManagementGridInterface
{
    public function userManagementGrid(Grid $grid): Grid
    {
        $promoteAction = new RowAction('Promote Roles', 'setUserRoles');
        $promoteAction->setRouteParametersMapping(['id' => $grid->getColumn('id')->getId()]);

        $demoteAction  = new RowAction('Demote Roles', 'demoteUserRoles');
        $demoteAction->setRouteParametersMapping(['id' => $grid->getColumn('id')->getId()]);

        $ownedProductsAction = new RowAction('Bought Products', 'boughtProductsByUser');
        $ownedProductsAction->setRouteParametersMapping(['id']);

        $grid->setHiddenColumns(['id', 'password', 'salt', 'confirmationToken', 'passwordRequestedAt', 'usernameCanonical', 'totalCheck', 'emailCanonical']);
        $grid->getColumn('username')->setOperators([Column::OPERATOR_SLIKE])->setTitle('Username');
        $grid->getColumn('roles')->setOperators([Column::OPERATOR_SLIKE])->setTitle('Roles');
        $grid->getColumn('enabled')->setOperators([Column::OPERATOR_EQ])->setTitle('Enabled');
        $grid->getColumn('lastLogin')->setTitle('Last Login')->setOperators([])->setFilterable(false);
        $grid->getColumn('email')->setTitle('Email')->setOperators([Column::OPERATOR_SLIKE]);

        $grid->getColumn('enabled')->manipulateRenderCell(function ($value, $row, $router) {
            return (bool) $value;
        })->setFilterable(false)
          ->setSize(5);

        $grid->getColumn('money')->manipulateRenderCell(function ($value, $row, $router) {
            return "$" . $value;
        })->setTitle('Money')
          ->setOperators([])
          ->setFilterable(false);

        /* @var Column $column */
        foreach ($grid->getColumns() as $column) {
            $column->setAlign('center');
        }

        $grid->addRowAction($ownedProductsAction);
        $grid->addRowAction($promoteAction);
        $grid->addRowAction($demoteAction);

        return $grid;
    }
}