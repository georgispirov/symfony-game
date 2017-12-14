<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\Promotion;
use AppBundle\Entity\User;
use AppBundle\Form\AddProductType;
use AppBundle\Form\UpdateProductType;
use AppBundle\Grid\ViewProductsByCategoryGrid;
use AppBundle\Services\OrderedProductsService;
use AppBundle\Services\ProductService;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Source\Vector;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{
    /**
     * @var ProductService
     */
    private $productService;

    /**
     * @var OrderedProductsService
     */
    private $orderedProductService;

    /**
     * @var Session
     */
    private $session;

    /**
     * ProductController constructor.
     * @param ProductService $productService
     * @param Session $session
     * @param OrderedProductsService $orderedProductService
     */
    public function __construct(ProductService $productService,
                                Session $session,
                                OrderedProductsService $orderedProductService)
    {
        $this->productService        = $productService;
        $this->orderedProductService = $orderedProductService;
        $this->session               = $session;
    }

    /**
     * @Route("/products/add", name="addProduct")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addProductsAction(Request $request)
    {
        $product = new Product();
        $form    = $this->createForm(AddProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('homepage');
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
            'products' => $data
        ]);
    }

    /**
     * @Route("/view/product", name="viewProduct")
     * @param Request $request
     * @return Response
     */
    public function previewProduct(Request $request): Response
    {
        $productID = $request->query->get('viewProductID');

        /* @var Product $product */
        $product   = $this->productService->getProductByID($productID);

        return $this->render(':products:view_product.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route("/update/product", name="updateProduct")
     * @param Request $request
     * @return Response
     */
    public function updateProductAction(Request $request): Response
    {
        $productID = $request->query->getInt('productID');
        $product   = $this->productService->getProductByID($productID);
        $user      = $this->get('security.token_storage')->getToken()->getUser(); /* @var User $user */
        $form      = $this->createForm(UpdateProductType::class, $product, ['method' => 'POST']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if (true === $this->productService->updateProduct($product, $user)) {
                $this->session->getFlashBag()->add('success-on-updating-product', 'Successfully updated product.');
                return $this->redirect($request->headers->get('referer'));
            }

            $this->session->getFlashBag()->add('failure-on-updating-product', 'Unable to update the product');
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
            $viewProductsByCategoryGrid->viewProductsByCategory($grid);
            return $grid->getGridResponse('products/products_by_category.html.twig', ['promotion' => $promotion]);
        }

        $this->session->getFlashBag()->add('no-products-in-active-promotion', 'There are no products in selected Active Promotion!');
        return $this->render('products/products_by_category.html.twig', ['promotion' => $promotion]);
    }
}
