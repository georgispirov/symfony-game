<?php

namespace AppBundle\Controller;

use AppBundle\Entity\OrderedProducts;
use AppBundle\Entity\Product;
use AppBundle\Services\CartService;
use AppBundle\Services\OrderedProductsService;
use AppBundle\Services\ProductService;
use APY\DataGridBundle\Grid\Column\DateTimeColumn;
use APY\DataGridBundle\Grid\Source\Vector;
use Doctrine\DBAL\Schema\Column;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

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
            $orderedProduct = $this->cartService->getOrderedProductByProduct($product);

            if (true === $this->cartService->increaseQuantityOnAlreadyBoughtItem($user, $orderedProduct, $product)) {
                $this->session->getFlashBag()->add('success', self::SUCCESSFULLY_ITEM_BOUGHT);
                return $this->redirect($request->headers->get('referer'));
            } else {
                return $this->redirect($request->headers->get('referer'));
            }

        }

        if (true === $this->cartService->addProduct($user, $product)) {
            $this->session->getFlashBag()->add('success', self::SUCCESSFULLY_ITEM_BOUGHT);
            return $this->redirect($request->headers->get('referer'));
        }

        $this->session->getFlashBag()->add('error', self::NON_SUCCESSFUL_ITEM_BOUGHT);
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
            $this->session->getFlashBag()->add('removedOrder', 'You have successfully removed the requested ordered product.');
            return $this->redirect($request->headers->get('referer'));
        }

        $this->session->getFlashBag()->add('nonRemovedOrder', 'Unable to remove the requested ordered product.');
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("products/update", name="updateOrderedProduct")
     * @param Request $request
     * @return bool
     */
    public function updateProductAction(Request $request): bool
    {
        $user    = $this->get('security.token_storage')->getToken()->getUser();
        $id      = $request->attributes->get('productID');
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
            $this->cartService->orderedProductsDataGrid($grid);
            return $grid->getGridResponse('cart/index.html.twig');
        }
        $this->session->getFlashBag()->add('info',
                                'No bought items added to your cart. Go and buy something.');

        return $this->render('cart/index.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function cartCheckoutAction(Request $request): Response
    {

    }
}
