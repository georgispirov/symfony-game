<?php

namespace AppBundle\Services;

use AppBundle\Entity\OrderedProducts;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Process\Exception\InvalidArgumentException;

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
     * @param User $user
     * @param Product $product
     * @return bool
     * @throws InvalidArgumentException
     */
    public function addProduct(User $user, Product $product): bool
    {
        if ( !$user instanceof User) {
            throw new InvalidArgumentException('Please provide valid User');
        }

        if ( !$product instanceof Product) {
            throw new InvalidArgumentException('Please provide valid Product');
        }

        if (true === $this->hasUserEnoughCash($product->getPrice(), $user->getMoney())) {
            return $this->orderedProducts->addOrderedProduct($user, $product);
        }
        return false;
    }

    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function removeProduct(User $user, Product $product): bool
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
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function updateProduct(User $user, Product $product): bool
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
     * @param User $user
     * @return array
     */
    public function getOrderedProductByUser(User $user): array
    {
        if ( !$user instanceof User) {
            throw new InvalidArgumentException('Please provide valid User.');
        }

        $orderedProducts = $this->orderedProducts->getOrdersByUser($user);

        if (sizeof($orderedProducts) > 0) {
            return $orderedProducts;
        }
        return [];
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
     * @param User $user
     * @return mixed
     */
    public function userCheckout(User $user)
    {
        // TODO: Implement userCheckout() method.
    }

    public function hasUserEnoughCash(float $itemPrice, float $userCash): bool
    {
        if ($itemPrice <= $userCash) {
            return true;
        }
        return false;
    }
}