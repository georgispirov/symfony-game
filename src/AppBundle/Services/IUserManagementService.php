<?php

namespace AppBundle\Services;

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
}