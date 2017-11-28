<?php

namespace AppBundle\Services;

use AppBundle\Entity\Categories;
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

    /**
     * @return array
     */
    public function getAllCategories() : array
    {
        return $this->em->getRepository(Categories::class)
                        ->findAll();
    }

    /**
     * @param int $id
     * @return object
     */
    public function getCategoryByID(int $id) : object
    {
        return $this->em->getRepository(Categories::class)
                        ->find($id);
    }
}