<?php

namespace AppBundle\Repository;

interface IUserRepository
{
    /**
     * @return array
     */
    public function getAllUsers(): array;
}