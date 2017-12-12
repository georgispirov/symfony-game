<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Promotion;
use AppBundle\Form\AddPromotionType;
use AppBundle\Services\ProductService;
use AppBundle\Services\PromotionService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class PromotionsController extends Controller
{
    const SUCCESSFULLY_ADDED_PRODUCT_PROMOTION   = 'Promotion has been successfully applied to selected Product.';

    const NON_SUCCESSFUL_ADDED_PRODUCT_PROMOTION = 'Failed while adding promotion to Product';

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
     * @param Request $request
     * @return Response
     */
    public function listAllAction(Request $request): Response
    {
        return $this->render(':promotions:list_promotions.html.twig');
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
                $promotion->addProductsToPromotion($product);
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
}
