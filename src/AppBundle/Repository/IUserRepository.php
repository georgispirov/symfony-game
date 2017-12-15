<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;

interface IUserRepository
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