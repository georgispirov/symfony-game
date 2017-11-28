<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CartController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/cart", name="cart-preview")
     */
    public function indexAction()
    {
        return $this->render('cart/index.html.twig');
    }
}
