<?php

namespace AppBundle\Services;

use AppBundle\Entity\Categories;
use AppBundle\Entity\OrderedProducts;
use AppBundle\Entity\Product;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\User;
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
        $this->em                = $em;
        $this->categoriesService = $categoriesService;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->em->getRepository(Product::class)
                        ->getAllActiveProducts();
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

    /**
     * @param int $id
     * @return null|Product
     */
    public function getProductByID(int $id)
    {
        return $this->em->getRepository(Product::class)
                        ->findProductByID($id);
    }

    /**
     * @param Product $product
     * @param User $user
     * @return bool
     */
    public function updateProduct(Product $product, User $user): bool
    {
        return $this->em->getRepository(Product::class)
                        ->updateProduct($product, $user);
    }

    /**
     * @param int $categoryID
     * @return array
     */
    public function getProductsByCategoryOnArray(int $categoryID): array
    {
        return $this->em->getRepository(Product::class)
                        ->getProductsByCategoryOnArray($categoryID);
    }

    /**
     * @param Promotion $promotion
     * @return array
     */
    public function getProductsByPromotion(Promotion $promotion): array
    {
        return $this->em->getRepository(Product::class)
                        ->getProductsByPromotion($promotion);
    }
}