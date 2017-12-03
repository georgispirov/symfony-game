<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\AddProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
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

        return $this->render('::add_product.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
