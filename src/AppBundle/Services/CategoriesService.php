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
     * @return Categories[]
     */
    public function getAllCategories(): array
    {
        return $this->em->getRepository(Categories::class)
                        ->findAll();
    }

    /**
     * @param int $id
     * @return null|Categories
     */
    public function getCategoryByID(int $id)
    {
        return $this->em->getRepository(Categories::class)
                        ->find($id);
    }

    /**
     * @param string $categoryName
     * @return null|Categories
     */
    public function getCategoryByName(string $categoryName)
    {
        return $this->em->getRepository(Categories::class)
                        ->findByCategoryName($categoryName);
    }
}