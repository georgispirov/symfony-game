<?php

namespace AppBundle\Controller;

use AppBundle\Entity\OrderedProducts;
use AppBundle\Form\SellBoughtProductType;
use AppBundle\Grid\SellBoughtProductsGrid;
use AppBundle\Services\OrderedProductsService;
use APY\DataGridBundle\Grid\Source\Vector;
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
        $grid = $this->get('grid');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $this->denyAccessUnlessGranted('ROLE_USER', $user, 'You do not have access to see this section.');

        $boughtProductsByUser = $this->orderedProductsService->getAllBoughtProductsByUser($user);

        if (sizeof($boughtProductsByUser) > 0) {
            $boughtProductsVector   = new Vector($boughtProductsByUser);
            $grid->setSource($boughtProductsVector);
            $boughtProductsGrid     = new SellBoughtProductsGrid();
            $boughtProductsGrid->configureSellBoughtGrid($grid);

            return $grid->getGridResponse(':bought_products:list_bought_products_by_user.html.twig');
        }

        $this->addFlash('non-existing-bought-products', 'There are no bought products.');
        return $this->render(':bought_products:list_bought_products_by_user.html.twig');
    }
}
