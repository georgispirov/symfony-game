<?php

namespace AppBundle\Repository;

use AppBundle\Entity\OrderedProducts;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Doctrine\ORM\QueryBuilder;

interface IOrderedProductsRepository
{
    /**
     * @param User $user
     * @return OrderedProducts[]
     */
    public function getOrderedProductsByUser(User $user): array;

    /**
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function addOrderedProduct(User $user, Product $product): bool;

    /**
     * @param Product $product
     * @return mixed
     */
    public function findOrderedProductByProduct(Product $product);

    public function findOrderedProductByID(int $id);

    /**
     * @param User $user
     * @param Product $product
     * @return mixed
     * @internal param int $id
     */
    public function findOrderedProductFromUserByID(User $user, Product $product);

    /**
     * @param OrderedProducts $orderedProducts
     * @param User $user
     * @param Product $product
     * @return bool
     */
    public function removeOrderedProduct(OrderedProducts $orderedProducts,
                                         User $user,
                                         Product $product): bool;

    /**
     * @param OrderedProducts $orderedProduct
     * @param Product $product
     * @return bool
     */
    public function increaseQuantity(OrderedProducts $orderedProduct, Product $product): bool;

    /**
     * @param OrderedProducts $orderedProduct
     * @param Product $product
     * @param User $user
     * @return bool
     */
    public function decreaseQuantity(OrderedProducts $orderedProduct,
                                     Product $product,
                                     User $user): bool;

    /**
     * @param User $user
     * @return float
     */
    public function getCheckoutFromAllProducts(User $user): float;

    /**
     * @param Product $product
     * @param User $user
     * @return float
     */
    public function getCheckoutFromSpecificProduct(Product $product, User $user): float;

    /**
     * @param User $user
     * @return array
     */
    public function getAllBoughtProductsByUser(User $user): array;

    /**
     * @param Product $product
     * @param OrderedProducts $orderedProducts
     * @param int $currentProductQuantity
     * @return bool
     */
    public function sellBoughtProduct(Product $product,
                                      OrderedProducts $orderedProducts,
                                      int $currentProductQuantity): bool;

    public function deleteOrderedProduct(OrderedProducts $orderedProducts): bool;

    /**
     * @param Product $product
     * @param User $user
     * @return null|OrderedProducts
     */
    public function getOrderedProductByProductAndUser(Product $product,
                                                      User $user);

    /**
     * @param OrderedProducts $orderedProducts
     * @return bool
     */
    public function updateBoughtProductOnSameUser(OrderedProducts $orderedProducts): bool;

    /**
     * @param OrderedProducts $requestedToUpdate
     * @param OrderedProducts $changedOrder
     * @return bool
     */
    public function updateBoughtProductOnChangedUser(OrderedProducts $requestedToUpdate,
                                                     OrderedProducts $changedOrder): bool;

    /**
     * @param User $changedUser
     * @param OrderedProducts $orderedProducts
     * @return bool
     */
    public function attachBoughtProductToUserWithoutContextOrder(User $changedUser,
                                                                 OrderedProducts $orderedProducts): bool;
}