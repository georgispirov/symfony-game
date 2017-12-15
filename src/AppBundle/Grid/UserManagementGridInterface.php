<?php

namespace AppBundle\Grid;

use APY\DataGridBundle\Grid\Grid;

interface UserManagementGridInterface
{
    public function userManagementGrid(Grid $grid): Grid;
}