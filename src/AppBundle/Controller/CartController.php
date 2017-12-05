<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Services\CartService;
use AppBundle\Services\OrderedProductsService;
use AppBundle\Services\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * CartController constructor.
     * @param CartService $cartService
     * @param ProductService $productService
     * @internal param $orderedProducts
     */
    public function __construct(CartService $cartService,
                                ProductService $productService)
    {
        $this->cartService    = $cartService;
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
            return $this->redirectToRoute('allProducts');
        }
        return $this->redirectToRoute('homepage');
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
     * @param int $id
     * @return array
     */
    public function getOrderedProductsByUserAction(int $id): array
    {
        return $this->cartService->getOrderedProductByUser($id);
    }
}
