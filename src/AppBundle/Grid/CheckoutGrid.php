<?php

namespace AppBundle\Grid;

use APY\DataGridBundle\Grid\Grid;

class CheckoutGrid implements CheckoutGridInterface
{
    /**
     * @param Grid $grid
     * @return Grid
     */
    public function configureCheckoutGrid(Grid $grid): Grid
    {
        return $grid;
    }
}