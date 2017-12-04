<?php

namespace AppBundle\Controller;

use AppBundle\Repository\ICategoriesRepository;
use AppBundle\Services\CategoriesService;
use AppBundle\Services\ICategoriesService;
use AppBundle\Services\IProductService;
use AppBundle\Services\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends Controller
{
    /**
     * @var ICategoriesRepository
     */
    private $categoryService;

    /**
     * @var IProductService
     */
    private $productService;

    /**
     * CategoriesController constructor.
     * @param CategoriesService|ICategoriesService $categoryService
     * @param IProductService|ProductService $productService
     */
    public function __construct(CategoriesService $categoryService,
                                ProductService $productService)
    {
        $this->categoryService = $categoryService;
        $this->productService  = $productService;
    }

    /**
     * @Route("/categories", name="showCategories")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function allCategoriesAction()
    {
        return $this->render('categories/index.html.twig', [
            'categories' => $this->categoryService->getAllCategories()
        ]);
    }

    /**
     * @Route("categories/{name}", name="applyCategories")
     * @param string $name
     * @param Request $request
     * @return Response
     */
    public function applyCategoryAction(string $name, Request $request)
    {
        $pagination = $this->get('knp_paginator');
        $data       = $this->productService->getProductsByCategory($name);

        $productsByCategory = $pagination->paginate($data,
                                                    $request->query->getInt('page', 1));

        return $this->render(':categories:selected_category.html.twig', [
            'selectedCategory' => $productsByCategory
        ]);
    }
}
