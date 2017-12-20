<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Categories;
use AppBundle\Form\AddCategoryType;
use AppBundle\Repository\ICategoriesRepository;
use AppBundle\Services\CategoriesService;
use AppBundle\Services\ICategoriesService;
use AppBundle\Services\IProductService;
use AppBundle\Services\ProductService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends Controller
{
    const SUCCESSFULLY_ADDED_CATEGORY   = 'You have successfully added requested category.';

    const UNSUCCESSFULLY_ADDED_CATEGORY = 'Failed adding requested category.';

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

        return $this->render(':products:all_products.html.twig', [
            'products'          => $productsByCategory,
            'categoryName'      => $name
        ]);
    }

    /**
     * @Route("/getProductsByCategory", name="getProductsByCategory")
     * @Method(methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getProductsByCategoryAction(Request $request): JsonResponse
    {
        $data = [];

        if (true === $request->isXmlHttpRequest()) {
            $categoryID = $request->request->get('categoryID');
            $data[]     = $this->productService->getProductsByCategoryOnArray($categoryID);
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/add/category", name="addCategory")
     * @param Request $request
     * @return Response
     */
    public function addCategoryAction(Request $request): Response
    {
        $category = new Categories();
        $form     = $this->createForm(AddCategoryType::class, $category, ['method' => 'POST']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (true === $this->categoryService->addCategory($category)) {
                $this->addFlash('successfully-added-category', self::SUCCESSFULLY_ADDED_CATEGORY);
                return $this->redirect($request->headers->get('referer'));
            }

            $this->addFlash('failed-adding-category', self::UNSUCCESSFULLY_ADDED_CATEGORY);
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render(':categories:add_category.html.twig', [
            'category' => $category,
            'form'     => $form->createView()
        ]);
    }
}
