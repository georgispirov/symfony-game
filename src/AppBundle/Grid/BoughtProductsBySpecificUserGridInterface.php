<?php

namespace AppBundle\Grid;

use APY\DataGridBundle\Grid\Grid;

interface BoughtProductsBySpecificUserGridInterface
{
    public function configureBoughtProductsBySpecificUser(Grid $grid): Grid;
}