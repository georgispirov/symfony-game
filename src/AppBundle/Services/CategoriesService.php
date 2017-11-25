<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManagerInterface;

class CategoriesService implements ICategoriesService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * CategoriesService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
}