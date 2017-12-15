<?php

namespace AppBundle\Services;

interface IUserManagementService
{
    /**
     * @return array
     */
    public function getAllUsers(): array;
}