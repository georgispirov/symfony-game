<?php

namespace AppBundle\Services;

use AppBundle\Entity\OrderedProducts;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
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
     * @return float
     */
    public function getCheckoutFromAllProducts(): float
    {

    }

    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function addOrderedProduct(User $user, Product $product): bool
    {
        return $this->em->getRepository(OrderedProducts::class)
                        ->addOrderedProduct($user, $product);
    }

    /**
     * @param OrderedProducts $product
     * @return bool
     */
    public function removeOrderedProduct(OrderedProducts $product): bool
    {
        return $this->em->getRepository(OrderedProducts::class)
                        ->removeOrderedProduct($product);

    }

    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function updateOrderProduct(User $user, Product $product): bool
    {
        // TODO: Implement updateOrderProduct() method.
    }

    /**
     * @param User $user
     * @return array
     */
    public function getOrdersByUser(User $user): array
    {
        return $this->em->getRepository(OrderedProducts::class)
                        ->getOrderedProductsByUser($user);
    }

    /**
     * @param Product $product
     * @return OrderedProducts|null|object
     */
    public function getOrderedProductByProduct(Product $product)
    {
        return $this->em->getRepository(OrderedProducts::class)
                        ->findOrderedProductByProduct($product);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getOrderedProductByID(int $id)
    {
        return $this->em->getRepository(OrderedProducts::class)
                        ->findOrderedProductByID($id);
    }

    /**
     * @param OrderedProducts $orderedProduct
     * @param Product $product
     * @return bool
     */
    public function increaseQuantity(OrderedProducts $orderedProduct, Product $product): bool
    {
        return $this->em->getRepository(OrderedProducts::class)
                        ->increaseQuantity($orderedProduct, $product);
    }

    /**
     * @param User $user
     * @param Product $product
     * @return OrderedProducts|null|object
     */
    public function getOrderByUserAndProduct(User $user, Product $product)
    {
        return $this->em->getRepository(OrderedProducts::class)
                        ->findOrderedProductFromUserByID($user, $product);
    }
}