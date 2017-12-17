<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
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

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(SessionInterface $session,
                                ContainerInterface $container,
                                EntityManagerInterface $em)
    {
        $this->session        = $session;
        $this->em             = $em;
        $this->container      = $container;
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

    /**
     * @param User $user
     * @return mixed
     */
    public function verifyUserWhenCheckout(User $user)
    {
        $passwordFromSessionUser  = $user->getPlainPassword();
        $userFromDb     = $this->em->getRepository(User::class)->findOneBy(['id' => $user->getId()]);
        $encoderService = $this->container->get('security.password_encoder');

        if (true === $encoderService->isPasswordValid($userFromDb, $passwordFromSessionUser)
            && $userFromDb->getEmail() === $user->getEmail()) {
                return true;
        }

        return false;
    }
}