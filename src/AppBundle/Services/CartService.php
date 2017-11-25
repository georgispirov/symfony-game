<?php

namespace AppBundle\Services;

use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class CartService implements ICartService
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
     * @var IOrderedProductsService
     */
    private $ordered;

    /**
     * CategoriesService constructor.
     * @param EntityManagerInterface $em
     * @param Session $session
     * @param IOrderedProductsService $ordered
     */
    public function __construct(EntityManagerInterface $em,
                                Session $session,
                                IOrderedProductsService $ordered)
    {
        $this->em      = $em;
        $this->session = $session;
        $this->ordered = $ordered;
    }

    /**
     * @param UserInterface $user
     * @param Product $product
     * @return bool
     */
    public function addProduct(UserInterface $user, Product $product): bool
    {
        // TODO: Implement addProduct() method.
    }

    /**
     * @param UserInterface $user
     * @param Product $product
     * @return bool
     */
    public function removeProduct(UserInterface $user, Product $product): bool
    {
        // TODO: Implement removeProduct() method.
    }

    /**
     * @param UserInterface $user
     * @return mixed
     */
    public function userCheckout(UserInterface $user)
    {
        // TODO: Implement userCheckout() method.
    }

    /**
     * @param array $products
     * @return mixed
     */
    public function getTotalOfProducts(array $products)
    {
        // TODO: Implement getTotalOfProducts() method.
    }
}