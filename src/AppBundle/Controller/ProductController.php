<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\AddProductType;
use AppBundle\Services\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{
    /**
     * @var ProductService
     */
    private $productService;

    /**
     * ProductController constructor.
     * @param $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
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
}
