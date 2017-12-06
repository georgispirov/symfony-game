<?php

namespace AppBundle\Services;

use AppBundle\Entity\OrderedProducts;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;

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
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function removeOrderedProduct(User $user, Product $product): bool
    {
        // TODO: Implement removeOrderedProduct() method.
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
     * @param int $id
     * @return mixed
     */
    public function getOrderedProductByID(int $id)
    {
        return $this->em->getRepository(OrderedProducts::class);
    }
}