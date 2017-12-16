<?php

namespace AppBundle\Grid;

use APY\DataGridBundle\Grid\Grid;
use Doctrine\ORM\EntityManagerInterface;

interface CartGridInterface
{
    /**
     * @param Grid $grid
     * @param EntityManagerInterface $em
     * @return Grid
     */
    public function orderedProductsDataGrid(Grid $grid, EntityManagerInterface $em): Grid;
}