<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class UserManagementService implements IUserManagementService
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(Session $session, EntityManager $em)
    {
        $this->session = $session;
        $this->em      = $em;
    }

    /**
     * @return array
     */
    public function getAllUsers(): array
    {
        return $this->em->getRepository(User::class)
                        ->getAllUsers();
    }

    /**
     * @param int $id
     * @return null|User
     */
    public function getUserByID(int $id)
    {
        return $this->em->getRepository(User::class)
                        ->getUserByID($id);
    }
}