<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Process\Exception\InvalidArgumentException;

class UserManagementService implements IUserManagementService
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(SessionInterface $session,
                                EntityManagerInterface $em)
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

    /**
     * @param User $user
     * @param array $roles
     * @return bool
     */
    public function updateUserRoles(User $user, array $roles): bool
    {
        if (sizeof($roles) < 1) {
            throw new InvalidArgumentException('At least one role must be selected to update the User.');
        }

        return $this->em->getRepository(User::class)
                        ->updateUserRoles($user, $roles);
    }

    /**
     * @param User $user
     * @param array $roles
     * @return bool
     */
    public function demoteUserRoles(User $user, array $roles): bool
    {
        if (sizeof($roles) < 1) {
            throw new InvalidArgumentException('At least one role must be selected to update the User.');
        }

        return $this->em->getRepository(User::class)
                        ->demoteUserRoles($user, $roles);
    }
}