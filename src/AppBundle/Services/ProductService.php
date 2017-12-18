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
                        ->getProductsByCategory($categoryName);
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

    /**
     * @param int $productID
     * @return array
     */
    public function getProductByIDOnArray(int $productID): array
    {
        return $this->em->getRepository(Product::class)
                        ->getProductByIDOnArray($productID);
    }

    /**
     * @param string $title
     * @return null|Product
     */
    public function getProductByTitle(string $title)
    {
        return $this->em->getRepository(Product::class)
                        ->getProductByTitle($title);
    }

    /**
     * @param OrderedProducts $orderedProducts
     * @param Product $product
     * @param int $quantity
     * @return bool
     */
    public function markAsOutOfStock(OrderedProducts $orderedProducts,
                                     Product $product,
                                     int $quantity): bool
    {
        if ($product->getQuantity() > 1) {
            return $this->decreaseQuantityOnProduct($orderedProducts, $product, $orderedProducts->getUser(), $quantity);
        }

        return $this->em->getRepository(Product::class)
                        ->markAsOutOfStock($orderedProducts, $product, $orderedProducts->getUser());
    }

    /**
     * @param OrderedProducts $orderedProducts
     * @param Product $product
     * @param User $user
     * @param int $quantity
     * @return bool
     */
    public function decreaseQuantityOnProduct(OrderedProducts $orderedProducts,
                                              Product $product,
                                              User $user,
                                              int $quantity): bool
    {
        if ($orderedProducts->getQuantity() < 2) {
            $this->em->getRepository(OrderedProducts::class)
                     ->removeOrderedProduct($orderedProducts, $orderedProducts->getUser(), $product);
        }

        return $this->em->getRepository(Product::class)
                        ->decreaseQuantityOnProduct($orderedProducts, $product, $orderedProducts->getUser(), $quantity);
    }

    /**
     * @param Promotion $promotion
     * @return array
     */
    public function getProductsByPromotionOnObjects(Promotion $promotion): array
    {
        return $this->em->getRepository(Product::class)
                        ->getProductsByPromotionOnObjects($promotion);
    }
}