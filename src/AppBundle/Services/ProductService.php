<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManagerInterface;

class ProductService implements IProductService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ProductService constructor.
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
}