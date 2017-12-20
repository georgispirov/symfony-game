<?php

namespace AppBundle\Grid;


use APY\DataGridBundle\Grid\Grid;

interface SellBoughtProductsGridInterface
{
    /**
     * @param Grid $grid
     * @return Grid
     */
    public function configureSellBoughtGrid(Grid $grid): Grid;
}