<?php

namespace AppBundle\Grid;

use AppBundle\Entity\User;
use APY\DataGridBundle\Grid\Grid;
use Doctrine\ORM\EntityManagerInterface;

interface CartGridInterface
{
    /**
     * @param Grid $grid
     * @param EntityManagerInterface $em
     * @param User $user
     * @return Grid
     */
    public function orderedProductsDataGrid(Grid $grid,
                                            EntityManagerInterface $em,
                                            User $user): Grid;
}