<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\DemoteUserRolesType;
use AppBundle\Form\UserManagementType;
use AppBundle\Grid\UserManagementGrid;
use AppBundle\Services\UserManagementService;
use APY\DataGridBundle\Grid\Source\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserManagementController extends Controller
{
    const SUCCESSFULLY_UPDATED_USER_ROLES = 'User roles has been successfully updated.';

    const NON_SUCCESSFULLY_UPDATED_USER_ROLES = 'Failed updating user roles.';

    const NOT_AUTHORIZED = 'You are not authorized to access this page';

    const SUCCESSFULLY_DEMOTED_USER_ROLES = 'Requested roles are successfully demoted from user.';

    const NON_SUCCESSFUL_DEMOTED_USER_ROLES = 'Failed demoting user roles.';

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var UserManagementService
     */
    private $userManagementService;

    public function __construct(SessionInterface $session,
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
        $userID = $request->query->get('id');
        $user   = $this->userManagementService->getUserByID($userID);
        $this->denyAccessUnlessGranted('ROLE_ADMIN', $user, self::NOT_AUTHORIZED);
        $form   = $this->createForm(UserManagementType::class, $user, [
                                                                    'method' => 'POST',
                                                                    'user'   => $user,
                                                                    'roles'  => $this->getParameter('security.role_hierarchy.roles')
                                                                 ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $getRequest = $request->request->all();
            $roles = $getRequest[$form->getName()]['roles'];

            if (true === $this->userManagementService->updateUserRoles($user, $roles)) {
                $this->addFlash('successfully-updated-user-roles', self::SUCCESSFULLY_UPDATED_USER_ROLES);
                return $this->redirect($request->headers->get('referer'));
            }

            $this->addFlash('non-successful-updated-user-roles', self::NON_SUCCESSFULLY_UPDATED_USER_ROLES);
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render(':user_management:set_user_roles.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/demote/userRoles", name="demoteUserRoles")
     * @param Request $request
     * @return Response
     */
    public function demoteUserRoles(Request $request): Response
    {
        $userID = $request->query->get('id');
        $user   = $this->userManagementService->getUserByID($userID);
        $this->denyAccessUnlessGranted('ROLE_ADMIN', $user, self::NOT_AUTHORIZED);

        $form = $this->createForm(DemoteUserRolesType::class, $user, [
                                                                    'method' => 'POST',
                                                                    'user'   => $user,
                                                                ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $getRequest = $request->request->all();
            $roles = $getRequest[$form->getName()]['roles'];

            if (true === $this->userManagementService->demoteUserRoles($user, $roles)) {
                $this->addFlash('successfully-demoted-user-roles', self::SUCCESSFULLY_DEMOTED_USER_ROLES);
                return $this->redirect($request->headers->get('referer'));
            }

            $this->addFlash('non-successful-demoted-user-roles', self::SUCCESSFULLY_DEMOTED_USER_ROLES);
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render(':user_management:demote_user_roles.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}