<?php

namespace AppBundle\Services;

use AppBundle\Entity\OrderedProducts;
use Doctrine\ORM\EntityManagerInterface;

class OrderedProductsService implements IOrderedProductsService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * OrderedProductsService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param OrderedProducts $oder
     * @return bool
     */
    public function addOrder(OrderedProducts $oder): bool
    {
        // TODO: Implement addOrder() method.
    }

    /**
     * @param OrderedProducts $oder
     * @return bool
     */
    public function removeOrder(OrderedProducts $oder): bool
    {
        // TODO: Implement removeOrder() method.
    }

    /**
     * @param OrderedProducts $oder
     * @return bool
     */
    public function updateOrder(OrderedProducts $oder): bool
    {
        // TODO: Implement updateOrder() method.
    }
}