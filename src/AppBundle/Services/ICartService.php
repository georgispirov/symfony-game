<?php

namespace AppBundle\Services;

use AppBundle\Entity\OrderedProducts;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use APY\DataGridBundle\Grid\Grid;

interface ICartService
{
    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function addProduct(User $user, Product $product) : bool;

    /**
     * @param User $user
     * @param OrderedProducts $orderedProduct
     * @param Product $product
     * @return bool
     */
    public function removeProduct(User $user, OrderedProducts $orderedProduct, Product $product) : bool;

    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function updateProduct(User $user, Product $product): bool;

    /**
     * @param User $user
     * @return mixed
     */
    public function userCheckout(User $user);

    /**
     * @param array $products
     * @return float
     */
    public function getTotalOfProducts(array $products): float;

    /**
     * @param User $user
     * @return OrderedProducts[]
     */
    public function getOrderedProductByUser(User $user): array;

    /**
     * @param Product $product
     * @return mixed
     */
    public function getOrderedProductByProduct(Product $product);

    /**
     * @param int $id
     * @return mixed
     */
    public function getOrderedProductByID(int $id);

    /**
     * @param float $itemPrice
     * @param float $userCash
     * @return bool
     */
    public function hasUserEnoughCash(float $itemPrice, float $userCash): bool;

    /**
     * @param Grid $grid
     * @return Grid
     */
    public function orderedProductsDataGrid(Grid $grid): Grid;

    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function isOrderedProductAlreadyBought(User $user, Product $product): bool;

    /**
     * @param User $user
     * @param OrderedProducts $orderedProduct
     * @param Product $product
     * @return bool
     */
    public function increaseQuantityOnAlreadyBoughtItem(User $user,
                                                        OrderedProducts $orderedProduct,
                                                        Product $product): bool;

    /**
     * @param User $user
     * @return float
     */
    public function getDifferenceMoneyAndOrderedProductsPrice(User $user): float;
}