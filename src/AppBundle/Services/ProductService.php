<?php

namespace AppBundle\Services;

use AppBundle\Entity\Categories;
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductService implements IProductService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var CategoriesService
     */
    private $categoriesService;

    /**
     * ProductService constructor.
     * @param EntityManagerInterface $em
     * @param CategoriesService $categoriesService
     */
    public function __construct(EntityManagerInterface $em,
                                CategoriesService $categoriesService)
    {
        $this->em = $em;
        $this->categoriesService = $categoriesService;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->em->getRepository(Product::class)
                        ->findAll();
    }

    /**
     * @param string $categoryName
     * @return Product[]
     */
    public function getProductsByCategory(string $categoryName): array
    {
        return $this->em->getRepository(Product::class)
                        ->findBy([
                                    'category' => $this->categoriesService
                                                       ->getCategoryByName($categoryName)
                        ]);
    }
}