<?php

namespace AppBundle\Services;

use AppBundle\Entity\Categories;
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\Process\Exception\LogicException;

class CategoriesService implements ICategoriesService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    /**
     * @var Session
     */
    private $session;

    /**
     * @var ProductService
     */
    private $productService;

    /**
     * CategoriesService constructor.
     * @param EntityManagerInterface $em
     * @param Session $session
     * @param ProductService $productService
     */
    public function __construct(EntityManagerInterface $em,
                                Session $session,
                                ProductService $productService)
    {
        $this->em             = $em;
        $this->session        = $session;
        $this->productService = $productService;
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

    /**
     * @return array
     */
    public function getAllCategoriesOnArray(): array
    {
        return $this->em->getRepository(Categories::class)
                        ->getAllCategoriesOnArray();
    }

    /**
     * @param Categories $categories
     * @return bool
     */
    public function removeCategoryWithoutProducts(Categories $categories): bool
    {
        return $this->em->getRepository(Categories::class)
                        ->removeCategoryWithoutProducts($categories);
    }

    /**
     * @param Categories $categories
     * @param Product[] $product
     * @return bool
     */
    public function removeCategoryWithProducts(Categories $categories, array $product): bool
    {
        foreach ($product as $p) {
            if (true === $this->productService->deleteProduct($p)) {
                return $this->em->getRepository(Categories::class)
                    ->removeCategoryWithoutProducts($categories);
            }
        }

        return $this->em->getRepository(Categories::class)
                        ->removeCategoryWithProducts($categories, $product);
    }
}