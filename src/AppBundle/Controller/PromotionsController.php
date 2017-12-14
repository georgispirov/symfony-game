<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Categories;
use AppBundle\Entity\Promotion;
use AppBundle\Form\AddPromotionType;
use AppBundle\Form\ProductsOnExistingPromotionType;
use AppBundle\Grid\PromotionsGrid;
use AppBundle\Services\ProductService;
use AppBundle\Services\PromotionService;
use APY\DataGridBundle\Grid\Source\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PromotionsController extends Controller
{
    const SUCCESSFULLY_ADDED_PRODUCT_PROMOTION   = 'Promotion has been successfully applied to selected Product.';

    const NON_SUCCESSFUL_ADDED_PRODUCT_PROMOTION = 'Failed while adding promotion to Product';

    const NON_EXISTING_PROMOTIONS = 'There are no active/non-active Promotions.';

    /**
     * @var PromotionService
     */
    private $promotionService;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var ProductService
     */
    private $productService;

    public function __construct(PromotionService $promotionService,
                                ProductService $productService,
                                Session $session)
    {

        $this->promotionService = $promotionService;
        $this->session          = $session;
        $this->productService   = $productService;
    }

    /**
     * @Route("/list/promotions", name="listPromotions")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return Response
     */
    public function listAllAction(Request $request): Response
    {
        $authorization = $this->get('security.authorization_checker');

        if (false === $authorization->isGranted('ROLE_ADMIN')) {
            throw new UnauthorizedHttpException('You must be logged in as User to preview this section.');
        }

        $promotions = $this->promotionService->getAllPromotions();
        $grid = $this->get('grid');
        $grid->setSource(new Entity('AppBundle:Promotion'));
        $promotionsGrid = new PromotionsGrid();
        $promotionsGrid->promotionsDataGrid($grid);

        if (sizeof($promotions) < 0) {
            $this->session->getFlashBag()->add('non-existing-promotions', self::NON_EXISTING_PROMOTIONS);
        }

        return $grid->getGridResponse('promotions/list_promotions.html.twig');
    }

    /**
     * @Route("/add/promotion", name="addPromotion")
     * @param Request $request
     * @return Response
     */
    public function addPromotionAction(Request $request): Response
    {
        $promotion = new Promotion();
        $form      = $this->createForm(AddPromotionType::class, $promotion, ['method' => 'POST']);
        $post      = $request->request->all();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($post['app_add_promotion']['product'] as $productID) {
                $product = $this->productService->getProductByID($productID);
                $product->addPromotionToProduct($promotion);
            }
            if (true === $this->promotionService->applyPromotionForProducts($promotion)) {
                $this->session->getFlashBag()->add('successfully-added-product-promotion', self::SUCCESSFULLY_ADDED_PRODUCT_PROMOTION);
                return $this->redirect($request->headers->get('referer'));
            }
            $this->session->getFlashBag()->add('failed-adding-product-promotion', self::NON_SUCCESSFUL_ADDED_PRODUCT_PROMOTION);
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('promotions/add_promotion.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/promotion", name="editPromotion")
     * @param Request $request
     * @return Response
     */
    public function editPromotionAction(Request $request): Response
    {
        return $this->render(':promotions:edit_promotion.html.twig');
    }

    /**
     * @Route("/remove/promotion", name="removePromotion")
     * @param Request $request
     * @return Response
     */
    public function removePromotionAction(Request $request): Response
    {
        return $this->render(':promotions:list_promotions.html.twig');
    }

    /**
     * @Route("/addCategoryToPromotion", name="addCategoryToPromotion")
     * @param Request $request
     * @return Response
     */
    public function addCategoryToPromotion(Request $request): Response
    {
        
    }

    /**
     * @Route("/products/toExistingPromotion", name="productsOnExistingPromotion")
     * @param Request $request
     * @return Response
     */
    public function addProductsToExistingPromotionAction(Request $request): Response
    {
        $promotion = new Promotion();
        $form = $this->createForm(ProductsOnExistingPromotionType::class, $promotion, ['method' => 'POST']);

        return $this->render('promotions/add_products_on_existing_promotion_html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/categories/toExistingPromotion", name="categoriesOnExistingPromotion")
     * @param Request $request
     * @return Response
     */
    public function addCategoriesToExistingPromotion(Request $request): Response
    {

    }

    /**
     * @Route("/nonExistingProducts/byPromotion", name="nonExistingProductsInPromotion")
     * @param Request $request
     * @return JsonResponse
     */
    public function getNonExistingProductsByPromotionAction(Request $request): JsonResponse
    {
        $data = [];

        if (true === $request->isXmlHttpRequest()) {
            $promotionID = $request->request->get('promotionID');
            $promotion   = $this->getDoctrine()->getRepository(Promotion::class)->getPromotionByID($promotionID);
            $data[]      = $this->promotionService->getNonExistingProductsInPromotion($promotion);
        }
        $this->get('logger')->error('hahaha', ['haha' => $data]);
        return new JsonResponse($data);
    }
}
