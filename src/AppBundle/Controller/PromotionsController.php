<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Promotion;
use AppBundle\Form\AddCategoryToPromotionType;
use AppBundle\Form\AddPromotionType;
use AppBundle\Form\ProductsOnExistingPromotionType;
use AppBundle\Grid\PromotionsGrid;
use AppBundle\Services\CategoriesService;
use AppBundle\Services\ProductService;
use AppBundle\Services\PromotionService;
use APY\DataGridBundle\Grid\Source\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PromotionsController extends Controller
{
    const SUCCESSFULLY_ADDED_PRODUCT_PROMOTION   = 'Promotion has been successfully applied to selected Product.';

    const SUCCESSFULLY_REMOVED_PRODUCT_FROM_PROMOTION = 'You have successfully removed requested product from promotion.';

    const SUCCESSFULLY_ADDED_PROMOTION_TO_CATEGORY = 'You have successfully added promotion to selected Category.';

    const SUCCESSFULLY_UPDATED_PROMOTION = 'You have successfully updated requested promotion.';

    const NON_SUCCESSFUL_ADDED_PRODUCT_PROMOTION = 'Failed while adding promotion to Product';

    const NON_EXISTING_PROMOTIONS = 'There are no active/non-active Promotions.';

    const NON_SUCCESSFULLY_REMOVED_PRODUCT_FROM_PROMOTION = 'Failed removing product from selected promotion.';

    const NON_SUCCESSFULLY_UPDATED_PROMOTION = 'Failed updating requested promotion';

    /**
     * @var PromotionService
     */
    private $promotionService;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var ProductService
     */
    private $productService;

    /**
     * @var CategoriesService
     */
    private $categoriesService;

    public function __construct(PromotionService $promotionService,
                                ProductService $productService,
                                CategoriesService $categoriesService,
                                SessionInterface $session)
    {

        $this->promotionService  = $promotionService;
        $this->session           = $session;
        $this->productService    = $productService;
        $this->categoriesService = $categoriesService;
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

        $promotions = $this->promotionService->getAllActivePromotions();

        $grid       = $this->get('grid');
        $grid->setSource(new Entity('AppBundle:Promotion'));
        $promotionsGrid = new PromotionsGrid();
        $promotionsGrid->promotionsDataGrid($grid);

        if (sizeof($promotions) < 1) {
            $this->addFlash('non-existing-promotions', self::NON_EXISTING_PROMOTIONS);
            return $this->render(':promotions:list_promotions.html.twig');
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
        $products  = [];

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($post['app_add_promotion']['product'] as $productID) {
                $product = $this->productService->getProductByID($productID);
                $products[] = $product;
                $product->addPromotionToProduct($promotion);
            }
            if (true === $this->promotionService->applyPromotionForProducts($promotion, $products)) {
                $this->addFlash('successfully-added-product-promotion', self::SUCCESSFULLY_ADDED_PRODUCT_PROMOTION);
                return $this->redirect($request->headers->get('referer'));
            }
            $this->addFlash('failed-adding-product-promotion', self::NON_SUCCESSFUL_ADDED_PRODUCT_PROMOTION);
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
        $promotionID = $request->query->get('id');
        $promotion   = $this->promotionService->getPromotionByID($promotionID);
        $form        = $this->createForm(AddPromotionType::class, $promotion, ['method' => 'POST']);
        $this->promotionService->configurePromotionUpdateForm($form);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (true === $this->promotionService->updatePromotion($promotion)) {
                $this->addFlash('successfully-updated-promotion', self::SUCCESSFULLY_UPDATED_PROMOTION);
                return $this->redirect($request->headers->get('referer'));
            }

            $this->addFlash('failed-updating-promotion', self::NON_SUCCESSFULLY_UPDATED_PROMOTION);
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render(':promotions:edit_promotion.html.twig', [
            'form'      => $form->createView(),
            'promotion' => $promotion
        ]);
    }

    /**
     * @Route("/remove/promotion", name="removePromotion")
     * @param Request $request
     * @return Response
     */
    public function removePromotionAction(Request $request): Response
    {
        $promotionID = $request->query->get('id');
        $promotion   = $this->promotionService->getPromotionByID($promotionID);
        $products    = $this->productService->getProductsByPromotionOnObjects($promotion);

        if (true === $this->promotionService->removePromotionForProducts($promotion, $products)) {
            $this->addFlash('removed-promotion-success', 'Successfully removed promotion');
            return $this->redirect($request->headers->get('referer'));
        }

        $this->addFlash('failed-removing-promotion', 'Failed removing promotion');
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/remove/productFromPromotion", name="removeProductsFromPromotion")
     * @param Request $request
     * @return Response
     */
    public function removeProductFromPromotionAction(Request $request): Response
    {
        $productID   = $request->query->get('id');
        $promotionID = $request->query->get('promotionID');

        $promotion   = $this->promotionService->getPromotionByID(intval($promotionID));
        $product     = $this->productService->getProductByID(intval($productID));

        if (true === $this->productService->removeProductFromPromotion($product, $promotion)) {
            $this->addFlash('successfully-removed-product-from-promotion', self::SUCCESSFULLY_REMOVED_PRODUCT_FROM_PROMOTION);
            return $this->redirect($request->headers->get('referer'));
        }

        $this->addFlash('non-successfully-removed-product-from-promotion', self::NON_SUCCESSFULLY_REMOVED_PRODUCT_FROM_PROMOTION);
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/addCategoryToPromotion", name="addCategoryToPromotion")
     * @param Request $request
     * @return Response
     */
    public function addCategoryToPromotion(Request $request): Response
    {
        $promotion   = new Promotion();
        $form        = $this->createForm(AddCategoryToPromotionType::class, $promotion);
        $requestData = $request->request->get($form->getName());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryID  = $requestData['category'];
            $category    = $this->categoriesService->getCategoryByID($categoryID);
            $productsInPromotion = $this->productService->getProductsByCategory($category);
            $isActivePromotion   = $requestData['isActive'];

            if (true === $this->promotionService->applyPromotionForCategory($promotion, $category, $productsInPromotion, $isActivePromotion)) {
                $this->addFlash('successfully-attached-category-to-promotion',self::SUCCESSFULLY_ADDED_PROMOTION_TO_CATEGORY);
                return $this->redirect($request->headers->get('referer'));
            }
        }

        return $this->render(':promotions:add_category_to_promotion.html.twig', [
            'promotion' => $promotion,
            'form'      => $form->createView()
        ]);
    }

    /**
     * @Route("/products/toExistingPromotion", name="productsOnExistingPromotion")
     * @param Request $request
     * @return Response
     */
    public function addProductsToExistingPromotionAction(Request $request): Response
    {
        $products       = [];
        $promotionID    = $request->query->get('id');
        $promotion      = $this->promotionService->getPromotionByID($promotionID);
        $activeProducts = $this->productService->getAll();
        $formConfigureOptions = ['method' => 'POST', 'promotion' => $promotion, 'activeProducts' => $activeProducts];
        $form        = $this->createForm(ProductsOnExistingPromotionType::class, $promotion, $formConfigureOptions);

        if ($requestData = $request->request->get($form->getName())) {
            $products = $this->promotionService->collectRequestProducts($requestData);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (true === $this->promotionService->applyProductsOnExistingPromotion($promotion, $products)) {
                $this->addFlash('added-product-to-existing-promotion', self::SUCCESSFULLY_ADDED_PRODUCT_PROMOTION);
                return $this->redirect($request->headers->get('referer'));
            }

            $this->addFlash('failed-adding-product-to-existing-promotion', self::NON_SUCCESSFUL_ADDED_PRODUCT_PROMOTION);
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('promotions/add_products_on_existing_promotion_html.twig',[
            'form'      => $form->createView(),
            'promotion' => $promotion
        ]);
    }
}
