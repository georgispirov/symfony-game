<?php

namespace AppBundle\Controller;

use AppBundle\Entity\OrderedProducts;
use AppBundle\Form\SellBoughtProductType;
use AppBundle\Grid\SellBoughtProductsGrid;
use AppBundle\Services\OrderedProductsService;
use AppBundle\Services\ProductService;
use APY\DataGridBundle\Grid\Source\Vector;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class OrderedProductsController extends Controller
{
    const SUCCESSFULLY_SELL_BOUGHT_PRODUCT = 'You have successfully sell requested product.';

    const NON_SUCCESSFUL_SELL_BOUGHT_PRODUCT = 'Failed selling requested product.';

    /**
     * @var OrderedProductsService
     */
    private $orderedProductsService;

    /**
     * @var ProductService
     */
    private $productService;

    public function __construct(OrderedProductsService $orderedProductsService,
                                ProductService $productService)
    {
        $this->orderedProductsService = $orderedProductsService;
        $this->productService         = $productService;
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
        $orderedProductID = $request->query->get('orderedProductID');
        /* @var OrderedProducts $orderedProduct */
        $orderedProduct  = $this->orderedProductsService->getOrderedProductByID($orderedProductID);

        if ($orderedProduct->getConfirmed() < 1) {
            throw new LogicException('There are no left quantity of this bought product.');
        }

        $formOptions     = ['method' => 'POST', 'orderedProduct' => $orderedProduct];
        $product         = $this->productService->getProductByID($orderedProduct->getProduct()->getId());
        $currentQuantity = $orderedProduct->getProduct()->getQuantity();
        $form            = $this->createForm(SellBoughtProductType::class, $product, $formOptions);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (true === $this->orderedProductsService->sellBoughtProduct($product, $orderedProduct, $currentQuantity)) {
                $this->addFlash('successfully-sell-product', self::SUCCESSFULLY_SELL_BOUGHT_PRODUCT);
                return $this->redirect($request->headers->get('referer'));
            }

            $this->addFlash('non-successfully-sell-product', self::NON_SUCCESSFUL_SELL_BOUGHT_PRODUCT);
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render(':products:sell_bought_product.html.twig', [
            'form'           => $form->createView(),
            'orderedProduct' => $orderedProduct
        ]);
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
