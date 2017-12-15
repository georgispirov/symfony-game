<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Grid\UserManagementGrid;
use AppBundle\Services\UserManagementService;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Source\Vector;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class UserManagementController extends Controller
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var UserManagementService
     */
    private $userManagementService;

    public function __construct(Session $session,
                                UserManagementService $userManagementService)
    {

        $this->session               = $session;
        $this->userManagementService = $userManagementService;
    }

    /**
     * @Route("/getAllUsers", name="getAllUsers")
     * @param Request $request
     * @return Response
     */
    public function listAllUsersAction(Request $request): Response
    {
        $grid   = $this->get('grid');
        $source = new Entity(User::class);
        $grid->setSource($source);
        $userManagementGrid = new UserManagementGrid();
        $userManagementGrid->userManagementGrid($grid);
        return $grid->getGridResponse(':user_management:list_all_users.html.twig');
    }

    /**
     * @Route("/set/userRoles", name="setUserRoles")
     * @param Request $request
     * @return Response
     */
    public function setUserRolesAction(Request $request): Response
    {
        return $this->render(':user_management:set_user_roles.html.twig');
    }
}