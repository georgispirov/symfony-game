<?php

namespace AppBundle\Controller;

use AppBundle\Entity\OrderedProducts;
use AppBundle\Entity\Product;
use AppBundle\Form\OrderedProductsCheckoutType;
use AppBundle\Grid\CartGrid;
use AppBundle\Grid\CheckoutGrid;
use AppBundle\Services\CartService;
use AppBundle\Services\OrderedProductsService;
use AppBundle\Services\ProductService;
use AppBundle\Services\UserManagementService;
use APY\DataGridBundle\Grid\Source\Vector;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends Controller
{
    const SUCCESSFULLY_ITEM_BOUGHT = 'You have successfully added this item to your cart.';
    const NON_SUCCESSFUL_ITEM_BOUGHT = 'You don\'t have enough money to buy this item.';

    /**
     * @var CartService
     */
    private $cartService;

    /**
     * @var ProductService
     */
    private $productService;

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

    /**
     * CartController constructor.
     * @param CartService $cartService
     * @param SessionInterface $session
     * @param UserManagementService $userManagementService
     * @param OrderedProductsService $orderedProductsService
     * @param ProductService $productService
     */
    public function __construct(CartService $cartService,
                                SessionInterface $session,
                                UserManagementService $userManagementService,
                                OrderedProductsService $orderedProductsService,
                                ProductService $productService)
    {
        $this->cartService            = $cartService;
        $this->session                = $session;
        $this->productService         = $productService;
        $this->userManagementService  = $userManagementService;
        $this->orderedProductsService = $orderedProductsService;
    }

    /**
     * @Route("products/orderProduct", name="addOrderedProduct")
     * @param Request $request
     * @return Response
     */
    public function addProductAction(Request $request)
    {
        $user    = $this->get('security.token_storage')->getToken()->getUser();
        $params  = $request->query->all();
        /* @var Product $product */
        $product = $this->productService->getProductByID($params['routeParams']);

        if (true === $this->cartService->isOrderedProductAlreadyBought($user, $product)) {
            $orderedProduct = $this->orderedProductsService->getOrderedProductByProductAndUser($product, $user);

            if (true === $this->cartService->increaseQuantityOnAlreadyBoughtItem($user, $orderedProduct, $product)) {
                $this->addFlash('success', self::SUCCESSFULLY_ITEM_BOUGHT);
                return $this->redirect($request->headers->get('referer'));
            } else {
                return $this->redirect($request->headers->get('referer'));
            }

        }

        if (true === $this->cartService->addProduct($user, $product)) {
            $this->addFlash('success', self::SUCCESSFULLY_ITEM_BOUGHT);
            return $this->redirect($request->headers->get('referer'));
        }

        $this->addFlash('error', self::NON_SUCCESSFUL_ITEM_BOUGHT);
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("products/remove", name="removeOrderedProduct")
     * @param Request $request
     * @return Response
     */
    public function removeProductAction(Request $request): Response
    {
        $user             = $this->get('security.token_storage')->getToken()->getUser();
        $id               = $request->query->get('orderedProductID');

        /* @var OrderedProducts $orderedProduct */
        $orderedProduct   = $this->cartService->getOrderedProductByID($id);
        $isRemoved        = $this->cartService->removeProduct($user, $orderedProduct, $orderedProduct->getProduct());

        if (true === $isRemoved) {
            $this->addFlash('removedOrder', 'You have successfully removed the requested ordered product.');
            return $this->redirect($request->headers->get('referer'));
        }

        $this->addFlash('nonRemovedOrder', 'Unable to remove the requested ordered product.');
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("cart/orderedProducts", name="showOrderedProductsByUser")
     * @param Request $request
     * @return Response
     */
    public function getOrderedProductsByUserAction(Request $request): Response
    {
        $grid    = $this->get('grid');
        $grid->setId('cartOrderedProducts');
        $user    = $this->get('security.token_storage')->getToken()->getUser();
        $orderedProducts      = $this->cartService->getOrderedProductByUser($user);

        if (sizeof($orderedProducts) > 0) {
            $totalCheckFromOrders = $this->orderedProductsService->getCheckoutFromAllProducts($user);
            $vector   = new Vector($orderedProducts);
            $grid->setSource($vector);
            $cartGrid = new CartGrid();
            $cartGrid->orderedProductsDataGrid($grid, $this->get('doctrine.orm.entity_manager'), $user);
            return $grid->getGridResponse('cart/index.html.twig', ['totalCheckFromOrders' => $totalCheckFromOrders]);
        }
        $this->addFlash('info', 'No bought items added to your cart. Go and buy something.');

        return $this->render('cart/index.html.twig');
    }

    /**
     * @Route("/cart/checkout", name="cartCheckout")
     * @param Request $request
     * @return Response
     */
    public function cartCheckoutAction(Request $request): Response
    {
        $requestData = $request->request->all();
        $user        = $this->get('security.token_storage')->getToken()->getUser();
        $this->denyAccessUnlessGranted('ROLE_USER', $user, UserManagementController::NOT_AUTHORIZED);
        $checkoutOrderedProducts = $referencedProducts = [];
        $orderedProductsPrice = 0;

        if ( !$this->cartService->hasOrderedProductsRequestedItems($requestData) ) {
            return $this->redirect($request->headers->get('referer'));
        }

        if ( !$this->cartService->hasItemBagInRequest($requestData) ) {
            return $this->redirect($request->headers->get('referer'));
        }

        $selectedProducts = $requestData['grid_cartOrderedProducts']['__action'];

        foreach (array_keys($selectedProducts) as $productID) {
            $checkoutOrderedProducts[] = $this->cartService->getOrderedProductByID($productID);
        }

        foreach ($checkoutOrderedProducts as $orderedProduct) { /* @var OrderedProducts $orderedProduct */
            $orderedProductsPrice += $orderedProduct->getOrderedProductPrice() / $orderedProduct->getQuantity();
            $referencedProducts[]  = $this->productService->getProductByID($orderedProduct->getProduct()->getId());
        }

        return $this->render('cart/cart_checkout.html.twig', [
            'orderedProducts'      => $checkoutOrderedProducts,
            'orderedProductsCost'  => $orderedProductsPrice
        ]);
    }

    /**
     * @Route("/apply/checkout", name="applyCheckout")
     * @param Request $request
     * @return JsonResponse
     */
    public function applyCheckoutAction(Request $request): JsonResponse
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $this->denyAccessUnlessGranted('ROLE_USER', $user, UserManagementController::NOT_AUTHORIZED);
        $orderedProducts = $request->request->get('orderedProducts');
        $isSuccessfully  = true;

        foreach ($orderedProducts as $orderRequest) {
            $product          = $this->productService->getProductByTitle($orderRequest['title']);
            $dbOrderedProduct = $this->orderedProductsService->getOrderedProductByProductAndUser($product, $user);

            if (false === $this->productService->markAsOutOfStock($dbOrderedProduct, $product, intval($orderRequest['quantity']))) {
                $isSuccessfully = false;
            }
        }

        if (true === $isSuccessfully) {
            $this->addFlash('successfully-purchased-item', 'You have successfully purchased selected items.');
            return new JsonResponse(true);
        }

        $this->addFlash('non-successfully-purchased-item', 'Failed purchasing selected items.');
        return new JsonResponse(false);
    }
}
