<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comments;
use AppBundle\Entity\Product;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\User;
use AppBundle\Form\AddCommentToProductType;
use AppBundle\Form\AddProductType;
use AppBundle\Form\UpdateProductType;
use AppBundle\Grid\ViewProductsByCategoryGrid;
use AppBundle\Services\CommentsService;
use AppBundle\Services\OrderedProductsService;
use AppBundle\Services\ProductService;
use APY\DataGridBundle\Grid\Source\Vector;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{
    const SUCCESSFULLY_ADDED_COMMENT     = 'Comment was successfully added to Product.';

    const NON_SUCCESSFULLY_ADDED_COMMENT = 'Failed adding comment on Product.';

    /**
     * @var ProductService
     */
    private $productService;

    /**
     * @var OrderedProductsService
     */
    private $orderedProductService;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var CommentsService
     */
    private $commentsService;

    /**
     * ProductController constructor.
     * @param ProductService $productService
     * @param SessionInterface $session
     * @param CommentsService $commentsService
     * @param OrderedProductsService $orderedProductService
     */
    public function __construct(ProductService $productService,
                                SessionInterface $session,
                                CommentsService $commentsService,
                                OrderedProductsService $orderedProductService)
    {
        $this->productService        = $productService;
        $this->orderedProductService = $orderedProductService;
        $this->session               = $session;
        $this->commentsService       = $commentsService;
    }

    /**
     * @Route("/products/add", name="addProduct")
     * @param Request $request
     * @return Response
     */
    public function addProductsAction(Request $request): Response
    {
        $product = new Product();
        $form    = $this->createForm(AddProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('successfully-added-product', 'You have successfully added requested product.');
            return $this->redirectToRoute('allProducts');
        }

        return $this->render(':products:add_product.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/allProducts", name="allProducts")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAllProducts(Request $request)
    {
        $paginate        = $this->get('knp_paginator');
        $allProducts     = $this->productService->getAll();
        $data            = $paginate->paginate($allProducts,
                                               $request->query
                                                       ->getInt('page', 1)
                                              );

        return $this->render(':products:all_products.html.twig', [
            'products'         => $data,
            'categoryName'     => 'All Products'
        ]);
    }

    /**
     * @Route("/view/product", name="viewProduct")
     * @param Request $request
     * @return Response
     */
    public function previewProduct(Request $request): Response
    {
        $comments  = new Comments();
        $productID = $request->query->get('viewProductID');
        $form      = $this->createForm(AddCommentToProductType::class, $comments, ['method' => 'POST']);

        $form->handleRequest($request);

        /* @var Product $product */
        $product   = $this->productService->getProductByID($productID);
        $existingCommentsOnProduct = $this->commentsService->getCommentsOnProduct($product);
        $user      = $this->get('security.token_storage')->getToken()->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            if (true === $this->commentsService->addCommentOnProduct($comments, $product, $user)) {
                $this->addFlash('successfully-added-comment-on-product', self::SUCCESSFULLY_ADDED_COMMENT);
                return $this->redirect($request->headers->get('referer'));
            }

            $this->addFlash('not-successfully-added-comment', self::NON_SUCCESSFULLY_ADDED_COMMENT);
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render(':products:view_product.html.twig', [
            'form'             => $form->createView(),
            'existingComments' => $existingCommentsOnProduct,
            'product'          => $product
        ]);
    }

    /**
     * @Route("/update/product", name="updateProduct")
     * @param Request $request
     * @return Response
     */
    public function updateProductAction(Request $request): Response
    {
        $productID = $request->query->getInt('viewProductID');
        $product   = $this->productService->getProductByID($productID);
        $user      = $this->get('security.token_storage')->getToken()->getUser(); /* @var User $user */
        $this->denyAccessUnlessGranted('ROLE_EDITOR', $user, 'Only Editors can update Products.');
        $form      = $this->createForm(UpdateProductType::class, $product, ['method' => 'POST', 'user' => $user]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if (true === $this->productService->updateProduct($product, $user)) {
                $this->addFlash('success-on-updating-product', 'Successfully updated product.');
                return $this->redirect($request->headers->get('referer'));
            }

            $this->addFlash('failure-on-updating-product', 'Unable to update the product');
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render(':products:update_product.html.twig', [
            'form'    => $form->createView(),
            'product' => $product
        ]);
    }

    /**
     * @Route("/getProductsByPromotion", name="productsByActivePromotion")
     * @param Request $request
     * @return Response
     */
    public function getProductsByPromotion(Request $request): Response
    {
        $authorization = $this->get('security.authorization_checker');

        $grid      = $this->get('grid');
        $promotion = $this->get('doctrine.orm.entity_manager')->getRepository(Promotion::class)
                                                              ->getPromotionByID($request->query->get('id'));

        $products  = $this->productService->getProductsByPromotion($promotion);

        if (sizeof($products) > 0) {
            $vector    = new Vector($products);
            $grid->setSource($vector);
            $viewProductsByCategoryGrid = new ViewProductsByCategoryGrid();
            $viewProductsByCategoryGrid->viewProductsByCategory($grid, $request);
            return $grid->getGridResponse('products/products_by_category.html.twig', ['promotion' => $promotion]);
        }

        $this->addFlash('no-products-in-active-promotion', 'There are no products in selected Active Promotion!');
        return $this->render('products/products_by_category.html.twig', ['promotion' => $promotion]);
    }

    /**
     * @Route("/remove/product", name="removeProduct")
     * @param Request $request
     * @return Response
     */
    public function deleteProductAction(Request $request): Response
    {
        $productID = $request->query->get('productID');
        $product   = $this->productService->getProductByID($productID);

        if (true === $this->productService->deleteProduct($product)) {
            $this->get('logger')->error('hahaha', ['haha' => 111]);
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->redirect($request->headers->get('referer'));
    }
}
