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
        if ( !$user instanceof User ) {
            throw new InvalidArgumentException('Please provide valid User');
        }

        if ( !$product instanceof Product ) {
            throw new InvalidArgumentException('Please provide valid Product');
        }

        if (true === $this->hasUserEnoughCash($product->getPrice(), $user->getMoney())) {
            return $this->orderedProducts->addOrderedProduct($user, $product);
        }
        return false;
    }

    /**
     * @param User $user
     * @param OrderedProducts $orderedProducts
     * @param Product $product
     * @return bool
     */
    public function removeProduct(User $user, OrderedProducts $orderedProducts, Product $product): bool
    {
        if ( !$user instanceof User ) {
            throw new InvalidArgumentException('Please provide valid User.');
        }

        if ( !$orderedProducts instanceof OrderedProducts ) {
            throw new InvalidArgumentException('Please provide valid Product.');
        }

        if ( !$product instanceof Product ) {
            throw new InvalidArgumentException('Please provide valid Ordered Product.');
        }

        if ($orderedProducts->getQuantity() > 1) {
            return $this->orderedProducts->decreaseQuantityOnOrderedProduct($orderedProducts, $product, $user);
        }

        if (true === $this->orderedProducts->removeOrderedProduct($orderedProducts, $user)) {
            return true;
        }

        return false;
    }

    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function updateProduct(User $user, Product $product): bool
    {
        if ( !$user instanceof User ) {
            return false;
        }

        if ( !$product instanceof OrderedProducts ) {
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
        if ( !$user instanceof User ) {
            throw new InvalidArgumentException('Please provide valid User.');
        }

        $orderedProducts = $this->orderedProducts->getOrdersByUser($user);

        if (sizeof($orderedProducts) > 0) {
            return $orderedProducts;
        }
        return [];
    }

    /**
     * @param Product $product
     * @return OrderedProducts|null|object
     */
    public function getOrderedProductByProduct(Product $product)
    {
        return $this->orderedProducts->getOrderedProductByProduct($product);
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

    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function isOrderedProductAlreadyBought(User $user, Product $product): bool
    {
        if ( !$user instanceof User ) {
            throw new InvalidArgumentException('Applied User must be a valid Entity');
        }

        if ( !$product instanceof Product ) {
            throw new InvalidArgumentException('Product must be a valid Entity');
        }

        $existingOrderedProduct = $this->orderedProducts
                                       ->getOrderByUserAndProduct($user, $product);

        if ($existingOrderedProduct instanceof OrderedProducts) {
            return true;
        }

        return false;
    }

    /**
     * @param User $user
     * @param OrderedProducts $orderedProduct
     * @param Product $product
     * @return bool
     */
    public function increaseQuantityOnAlreadyBoughtItem(User $user,
                                                        OrderedProducts $orderedProduct,
                                                        Product $product): bool
    {
        if ( !$user instanceof User ) {
            throw new InvalidArgumentException('User must be a valid Entity.');
        }

        if ( !$orderedProduct instanceof OrderedProducts ) {
            throw new InvalidArgumentException('Ordered Product must be a valid Entity.');
        }

        if ( !$product instanceof Product) {
            throw new InvalidArgumentException('Product must be a valid Entity.');
        }

        $sumFromProductAndAllOrderedProducts = 0;

        if (0 !== $user->getTotalCheck()) {
            $sumFromProductAndAllOrderedProducts = $product->getPrice() + $this->orderedProducts->getCheckoutFromAllProducts($user);
        }

        if ($user->getMoney() < $product->getPrice() || $user->getMoney() < $sumFromProductAndAllOrderedProducts) {
            $this->session->getFlashBag()->add('not-enough-money', 'You don\'t have enough money to order this item, please check your cart.');
            return false;
        }

        return $this->orderedProducts->increaseQuantity($orderedProduct, $product);
    }

    /**
     * @param User $user
     * @return float
     */
    public function getDifferenceMoneyAndOrderedProductsPrice(User $user): float
    {
        return $user->getMoney() - $this->orderedProducts->getCheckoutFromAllProducts($user);
    }

    /**
     * @param array $requestItems
     * @return bool
     */
    public function hasOrderedProductsRequestedItems(array $requestItems): bool
    {
        return isset($requestItems['grid_cartOrderedProducts']['__action']);
    }

    /**
     * @param array $requestItemBag
     * @return bool
     */
    public function hasItemBagInRequest(array $requestItemBag): bool
    {
       return (sizeof($requestItemBag['grid_cartOrderedProducts']['__action']) > 0);
    }
}