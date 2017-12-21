<?php

namespace AppBundle\Services;

use AppBundle\Entity\OrderedProducts;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderedProductsService implements IOrderedProductsService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * OrderedProductsService constructor.
     * @param EntityManagerInterface $em
     * @param SessionInterface $session
     */
    public function __construct(EntityManagerInterface $em,
                                SessionInterface $session)
    {
        $this->em      = $em;
        $this->session = $session;
    }


    /**
     * @param User $user
     * @return float
     */
    public function getCheckoutFromAllProducts(User $user): float
    {
        return $this->em->getRepository(OrderedProducts::class)
                        ->getCheckoutFromAllProducts($user);
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
     * @param OrderedProducts $orderedProducts
     * @param User $user
     * @return bool
     */
    public function removeOrderedProduct(OrderedProducts $orderedProducts, User $user): bool
    {
        return $this->em->getRepository(OrderedProducts::class)
                        ->removeOrderedProduct($orderedProducts, $user, $orderedProducts->getProduct());

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


    /**
     * @param OrderedProducts $orderedProducts
     * @param Product $product
     * @param User $user
     * @return bool
     */
    public function decreaseQuantityOnOrderedProduct(OrderedProducts $orderedProducts,
                                                     Product $product,
                                                     User $user): bool
    {
        return $this->em->getRepository(OrderedProducts::class)
                        ->decreaseQuantity($orderedProducts, $product, $user);
    }

    /**
     * @param User $user
     * @return array
     */
    public function getAllBoughtProductsByUser(User $user): array
    {
        return $this->em->getRepository(OrderedProducts::class)
                        ->getAllBoughtProductsByUser($user);
    }

    /**
     * @param Product $product
     * @param OrderedProducts $orderedProducts
     * @param int $currentProductQuantity
     * @return bool
     */
    public function sellBoughtProduct(Product $product,
                                      OrderedProducts $orderedProducts,
                                      int $currentProductQuantity): bool
    {
        return $this->em->getRepository(OrderedProducts::class)
                        ->sellBoughtProduct($product, $orderedProducts, $currentProductQuantity);
    }

    /**
     * @param Product $product
     * @param User $user
     * @return null|OrderedProducts
     */
    public function getOrderedProductByProductAndUser(Product $product,
                                                      User $user)
    {
        return $this->em->getRepository(OrderedProducts::class)
                        ->getOrderedProductByProductAndUser($product, $user);
    }
}