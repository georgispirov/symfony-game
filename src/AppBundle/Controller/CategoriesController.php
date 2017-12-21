<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Categories;
use AppBundle\Form\AddCategoryType;
use AppBundle\Grid\CategoriesGrid;
use AppBundle\Repository\ICategoriesRepository;
use AppBundle\Services\CategoriesService;
use AppBundle\Services\ICategoriesService;
use AppBundle\Services\IProductService;
use AppBundle\Services\ProductService;
use APY\DataGridBundle\Grid\Source\Vector;
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

    /**
     * @Route("/list/categories", name="listCategories")
     * @param Request $request
     * @return Response
     */
    public function allCategoriesGridAction(Request $request): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $this->denyAccessUnlessGranted('ROLE_EDITOR', $user, 'Only Editors can manage Categories');
        $grid = $this->get('grid');
        $allCategories = $this->categoryService->getAllCategoriesOnArray();

        if ($allCategories) {
            $vectorCategories = new Vector($allCategories);
            $grid->setSource($vectorCategories);
            $categoriesGrid   = new CategoriesGrid();
            $categoriesGrid->configureCategoriesGrid($grid);

            return $grid->getGridResponse(':categories:categories_management.html.twig');
        }

        $this->addFlash('non-active-categories', 'There are no Categories.');
        return $this->render('categories/categories_management.html.twig');
    }

    /**
     * @Route("/category/delete", name="deleteCategory")
     * @param Request $request
     * @return Response
     */
    public function deleteCategoryAction(Request $request): Response
    {
        $categoryID = $request->query->get('id');
        $category   = $this->categoryService->getCategoryByID($categoryID);
        $productsInCategory = $this->productService->getAllNonActiveAndOutOfStockProductsByCategory($category);

        if (sizeof($productsInCategory) > 0) {
            if (true === $this->categoryService->removeCategoryWithProducts($category, $productsInCategory)) {
                $this->addFlash('successfully-removed-category', 'You have successfully removed requested Category.');
                return $this->redirect($request->headers->get('referer'));
            }

            $this->addFlash('non-successful-removed-category', 'You have successfully removed requested Category.');
            return $this->redirect($request->headers->get('referer'));
        }

        if (true === $this->categoryService->removeCategoryWithoutProducts($category)) {
            $this->addFlash('successfully-removed-category', 'You have successfully removed requested Category.');
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->redirect($request->headers->get('referer'));
    }
}
