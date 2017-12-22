<?php

namespace AppBundle\Controller;

use AppBundle\Entity\OrderedProducts;
use AppBundle\Entity\User;
use AppBundle\Form\DemoteUserRolesType;
use AppBundle\Form\UpdateBoughtProductFromUserType;
use AppBundle\Form\UserManagementType;
use AppBundle\Grid\BoughtProductsBySpecificUserGrid;
use AppBundle\Grid\UserManagementGrid;
use AppBundle\Services\OrderedProductsService;
use AppBundle\Services\UserManagementService;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Source\Vector;
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

    /**
     * @var OrderedProductsService
     */
    private $orderedProductsService;

    public function __construct(SessionInterface $session,
                                UserManagementService $userManagementService,
                                OrderedProductsService $orderedProductsService)
    {

        $this->session                = $session;
        $this->userManagementService  = $userManagementService;
        $this->orderedProductsService = $orderedProductsService;
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

    /**
     * @Route("/bought/productsByUser", name="boughtProductsByUser")
     * @param Request $request
     * @return Response
     */
    public function boughtProductsBySpecificUser(Request $request): Response
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $this->denyAccessUnlessGranted('ROLE_ADMIN', $currentUser, 'You are not authorized to access this page. Only Admins has access.');

        $userID = $request->query->get('id');
        $user   = $this->userManagementService->getUserByID($userID);
        $boughtProductsByUser = $this->orderedProductsService->getAllBoughtProductsByUser($user);

        if (sizeof($boughtProductsByUser) > 0) {
            $grid   = $this->get('grid');
            $vectorSource = new Vector($boughtProductsByUser);
            $grid->setSource($vectorSource);
            $boughtProductsByUserGrid = new BoughtProductsBySpecificUserGrid();
            $boughtProductsByUserGrid->configureBoughtProductsBySpecificUser($grid);

            return $grid->getGridResponse(':bought_products:list_bought_products_by_user.html.twig');
        }

        $this->addFlash('non-existing-bought-products', 'There are no bought products by this User.');
        return $this->render(':bought_products:list_bought_products_by_user.html.twig');
    }

    /**
     * @Route("/updateBoughtProduct/BySpecificUser", name="updateBoughtProductOnUser")
     * @param Request $request
     * @return Response
     */
    public function updateBoughtProductOnSpecificUserAction(Request $request): Response
    {
        $loggedUser = $this->get('security.token_storage')->getToken()->getUser();
        $this->denyAccessUnlessGranted('ROLE_ADMIN', $loggedUser, 'Only Admins are granted to perform this action.');

        $orderedProductID = $request->query->get('orderedProductID');
        /* @var OrderedProducts $orderedProduct */
        $orderedProduct   = $this->orderedProductsService->getOrderedProductByID($orderedProductID);
        $currentUser      = ($this->orderedProductsService->getOrderedProductByID($orderedProductID))->getUser();
        $configureFormOptions = ['method'      => 'POST', 'userOwn' => $orderedProduct->getUser(),
                                 'confirmed'   => $orderedProduct->getConfirmed(), 'quantity' => $orderedProduct->getQuantity()];

        $form = $this->createForm(UpdateBoughtProductFromUserType::class, $orderedProduct, $configureFormOptions);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (true === $this->userManagementService->updateBoughtProductByUser($currentUser, $orderedProduct)) {
                $this->addFlash('successfully-updated-bought-product', 'You have successfully updated requested bought product.');
                return $this->redirect($request->headers->get('referer'));
            }

            $this->addFlash('failed-updating-bought-product', 'Failed updating bought product.');
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('user_management/update_bought_product_from_user.html.twig', [
            'form'           => $form->createView(),
            'orderedProduct' => $orderedProduct
        ]);
    }

    /**
     * @Route("/boughtItems/delete", name="deleteBoughtProductOnUser")
     * @param Request $request
     * @return Response
     */
    public function deleteBoughtProductOnUserAction(Request $request): Response
    {
        $orderedProductID = $request->query->get('orderedProductID');
        $orderedProduct   = $this->orderedProductsService->getOrderedProductByID($orderedProductID);

        if (true === $this->userManagementService->removeOrderedBoughtOrderedProduct($orderedProduct)) {
            $this->addFlash('successfully-removed-ordered-bought-product', 'You have successfully removed bought product.');
            return $this->redirect($request->headers->get('referer'));
        }

        $this->addFlash('failed-removed-ordered-bought-product', 'You have successfully removed bought product.');
        return $this->redirect($request->headers->get('referer'));
    }
}