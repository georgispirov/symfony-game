<?php

namespace AppBundle\Services;

use AppBundle\Entity\OrderedProducts;

interface IOrderedProductsService
{
    /**
     * @param OrderedProducts $oder
     * @return bool
     */
    public function addOrder(OrderedProducts $oder) : bool;

    /**
     * @param OrderedProducts $oder
     * @return bool
     */
    public function removeOrder(OrderedProducts $oder) : bool;

    /**
     * @param OrderedProducts $oder
     * @return bool
     */
    public function updateOrder(OrderedProducts $oder) : bool;
}