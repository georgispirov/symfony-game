<?php

namespace AppBundle\Services;

use AppBundle\Entity\OrderedProducts;
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService implements ICartService
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
     * @var OrderedProductsService
     */
    private $orderedProducts;

    /**
     * CartService constructor.
     * @param EntityManagerInterface $em
     * @param SessionInterface $session
     * @param OrderedProductsService $orderedProducts
     */
    public function __construct(EntityManagerInterface $em,
                                SessionInterface $session,
                                OrderedProductsService $orderedProducts)
    {
        $this->em              = $em;
        $this->session         = $session;
        $this->orderedProducts = $orderedProducts;
    }

    /**
     * @param UserInterface $user
     * @param Product $product
     * @return bool
     */
    public function addProduct(UserInterface $user, Product $product): bool
    {
        if (null === $user) {
            return false;
        }

        if (null === $product) {
            return false;
        }

        return $this->orderedProducts->addOrderedProduct($user, $product);
    }

    /**
     * @param UserInterface $user
     * @param Product $product
     * @return bool
     */
    public function removeProduct(UserInterface $user, Product $product): bool
    {
        if (null === $user) {
            return false;
        }

        if (null === $product) {
            return false;
        }

        return $this->orderedProducts->removeOrderedProduct($user, $product);
    }

    /**
     * @param UserInterface $user
     * @param Product $product
     * @return bool
     */
    public function updateProduct(UserInterface $user, Product $product): bool
    {
        if (null === $user) {
            return false;
        }

        if (null === $product) {
            return false;
        }

        return $this->orderedProducts->updateOrderProduct($user, $product);
    }

    /**
     * @param int $id
     * @return OrderedProducts[]
     */
    public function getOrderedProductByUser(int $id): array
    {
        return $this->orderedProducts->getOrdersByUser($id);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getOrderedProductByID(int $id)
    {
        return $this->orderedProducts->getOrderedProductByID($id);
    }

    /**
     * @param array $products
     * @return mixed
     */
    public function getTotalOfProducts(array $products): float
    {
        return $this->orderedProducts->getCheckoutFromAllProducts();
    }

    /**
     * @param UserInterface $user
     * @return mixed
     */
    public function userCheckout(UserInterface $user)
    {
        // TODO: Implement userCheckout() method.
    }
}