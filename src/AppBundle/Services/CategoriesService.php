<?php

namespace AppBundle\Services;

use AppBundle\Entity\Categories;
use APY\DataGridBundle\Grid\Exception\ColumnAlreadyExistsException;
use APY\DataGridBundle\Grid\Exception\TypeAlreadyExistsException;
use Doctrine\DBAL\Exception\DatabaseObjectExistsException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\Process\Exception\LogicException;

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

    /**
     * @param Categories $categories
     * @return bool
     */
    public function addCategory(Categories $categories): bool
    {
        if ( !$categories instanceof Categories ) {
            throw new InvalidArgumentException('Applied Category must be a valid entity.');
        }
        $isCategoryExists = $this->em->getRepository(Categories::class)
                                     ->findOneBy([
                                        'name' => $categories->getName()
                                     ]);

        if ( $isCategoryExists instanceof Categories ) {
            throw new LogicException('Category with this name already exists.');
        }

        return $this->em->getRepository(Categories::class)
                        ->addCategory($categories);
    }
}