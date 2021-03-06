<?php

namespace AppBundle\Services;

use AppBundle\Entity\OrderedProducts;
use AppBundle\Entity\User;

interface IUserManagementService
{
    /**
     * @return array
     */
    public function getAllUsers(): array;

    /**
     * @param int $id
     * @return null|User
     */
    public function getUserByID(int $id);

    /**
     * @param User $user
     * @param array $roles
     * @return bool
     */
    public function updateUserRoles(User $user, array $roles): bool;

    /**
     * @param User $user
     * @param array $roles
     * @return bool
     */
    public function demoteUserRoles(User $user, array $roles): bool;

    /**
     * @param User $user
     * @return mixed
     */
    public function verifyUserWhenCheckout(User $user);

    /**
     * @param User $currentOwnUser
     * @param OrderedProducts $orderedProducts
     * @return bool
     */
    public function updateBoughtProductByUser(User $currentOwnUser,
                                              OrderedProducts $orderedProducts): bool;
}