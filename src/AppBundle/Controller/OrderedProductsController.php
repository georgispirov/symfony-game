<?php

namespace AppBundle\Controller;

use AppBundle\Entity\OrderedProducts;
use AppBundle\Form\SellBoughtProductType;
use AppBundle\Services\OrderedProductsService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderedProductsController extends Controller
{
    /**
     * @var OrderedProductsService
     */
    private $orderedProductsService;

    public function __construct(OrderedProductsService $orderedProductsService)
    {
        $this->orderedProductsService = $orderedProductsService;
    }

    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }

    /**
     * @Route("/calculate/order", name="calculateOrder")
     * @param Request $request
     * @return JsonResponse
     */
    public function calculateOrdersFromCheckoutAction(Request $request): JsonResponse
    {
        $orderedProductID = $request->request->get('orderID');
        /* @var OrderedProducts $orderedProduct */
        $orderedProduct   = $this->orderedProductsService->getOrderedProductByID($orderedProductID);
        return new JsonResponse($orderedProduct->getOrderedProductPrice() / $orderedProduct->getQuantity());
    }

    /**
     * @Route("/sell/boughtProduct", name="sellBoughtProduct")
     * @param Request $request
     * @return Response
     */
    public function sellBoughtProductAction(Request $request): Response
    {
        $form = $this->createForm(SellBoughtProductType::class);
    }

    /**
     * @Route("/show/boughtProducts", name="showBoughtProducts")
     * @param Request $request
     * @return Response
     */
    public function showBoughtProductsAction(Request $request): Response
    {
        
    }
}
