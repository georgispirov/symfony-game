<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Services\CartService;
use AppBundle\Services\OrderedProductsService;
use AppBundle\Services\ProductService;
use APY\DataGridBundle\Grid\Source\Vector;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends Controller
{
    /**
     * @var OrderedProductsService
     */
    private $cartService;

    /**
     * @var ProductService
     */
    private $productService;

    /**
     * @var Session
     */
    private $session;

    /**
     * CartController constructor.
     * @param CartService $cartService
     * @param Session $session
     * @param ProductService $productService
     * @internal param $orderedProducts
     */
    public function __construct(CartService $cartService,
                                Session $session,
                                ProductService $productService)
    {
        $this->cartService    = $cartService;
        $this->session        = $session;
        $this->productService = $productService;
    }

    /**
     * @Route("products/add", name="addOrderedProduct")
     * @param Request $request
     * @return Response
     */
    public function addProductAction(Request $request)
    {
        $user    = $this->get('security.token_storage')->getToken()->getUser();
        $params  = $request->query->all();
        $product = $this->productService->getProductByID($params['routeParams']);

        if (true === $this->cartService->addProduct($user, $product)) {
            $this->session->getFlashBag()->add('success', 'You have successfully added this item to your cart.');
            return $this->redirect($request->headers->get('referer'));
        }
        $this->session->getFlashBag()->add('error', 'You don\'t have enough money to buy this item.');
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("products/remove", name="removeOrderedProduct")
     * @param Request $request
     * @return bool
     */
    public function removeProductAction(Request $request): bool
    {
        $user    = $this->get('security.token_storage')->getToken()->getUser();
        $id      = $request->attributes->get('_route_params');
        $product = $this->cartService->getOrderedProductByID($id);

        return $this->cartService->removeProduct($user, $product);
    }

    /**
     * @Route("products/update", name="updateOrderedProduct")
     * @param Request $request
     * @return bool
     */
    public function updateProductAction(Request $request): bool
    {
        $user    = $this->get('security.token_storage')->getToken()->getUser();
        $id      = $request->attributes->get('_route_params');
        $product = $this->cartService->getOrderedProductByID($id);

        return $this->cartService->updateProduct($user, $product);
    }

    /**
     * @Route("cart/orderedProducts", name="showOrderedProductsByUser")
     * @param Request $request
     * @return Response
     */
    public function getOrderedProductsByUserAction(Request $request): Response
    {
        $grid    = $this->get('grid');
        $user    = $this->get('security.token_storage')->getToken()->getUser();
        $orderedProducts = $this->cartService->getOrderedProductByUser($user);

        if (sizeof($orderedProducts) > 0) {
            $vector = new Vector($orderedProducts);
            $grid->setSource($vector);
            return $grid->getGridResponse('cart/index.html.twig');
        }
        $this->session->getFlashBag()->add('notice',
                                'No bought items added to your cart. Go and buy something.');

        return $this->redirect($request->headers->get('referer'));
    }
}
