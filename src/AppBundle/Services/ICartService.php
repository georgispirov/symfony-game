<?php

namespace AppBundle\Services;

use AppBundle\Entity\Product;
use FOS\UserBundle\Model\UserInterface;

interface ICartService
{
    /**
     * @param UserInterface $user
     * @param Product $product
     * @return bool
     */
    public function addProduct(UserInterface $user, Product $product) : bool;

    /**
     * @param UserInterface $user
     * @param Product $product
     * @return bool
     */
    public function removeProduct(UserInterface $user, Product $product) : bool;

    /**
     * @param UserInterface $user
     * @return mixed
     */
    public function userCheckout(UserInterface $user);

    /**
     * @param array $products
     * @return mixed
     */
    public function getTotalOfProducts(array $products);
}