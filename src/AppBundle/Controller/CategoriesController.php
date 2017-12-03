<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Categories;
use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends Controller
{
    /**
     * @Route("/categories", name="showCategories")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('categories/index.html.twig', [
            'categories' => $em->getRepository(Categories::class)->findAll()
        ]);
    }

    /**
     * @Route("categories/{name}", name="applyCategories")
     * @param string $name
     * @return Response
     */
    public function applyCategoryAction(string $name)
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render(':categories:selected_category.html.twig', [
            'selectedCategory' => $em->getRepository(Product::class)->getProductsByCategory($name)
        ]);
    }
}
