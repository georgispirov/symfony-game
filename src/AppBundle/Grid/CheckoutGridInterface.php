<?php

namespace AppBundle\Grid;

use APY\DataGridBundle\Grid\Grid;

interface CheckoutGridInterface
{
    /**
     * @param Grid $grid
     * @return Grid
     */
    public function configureCheckoutGrid(Grid $grid): Grid;
}