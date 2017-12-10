<?php

namespace AppBundle\Controller;

use AppBundle\Services\PromotionService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class PromotionsController extends Controller
{
    /**
     * @var PromotionService
     */
    private $promotionService;

    /**
     * @var Session
     */
    private $session;

    public function __construct(PromotionService $promotionService,
                                Session $session)
    {

        $this->promotionService = $promotionService;
        $this->session          = $session;
    }

    /**
     * @Route("/list/promotions", name="listPromotions")
     * @param Request $request
     * @return Response
     */
    public function listAllAction(Request $request): Response
    {
        return $this->render(':promotions:list_promotions.html.twig');
    }

    /**
     * @Route("/add/promotion", name="addPromotion")
     * @param Request $request
     * @return Response
     */
    public function addPromotionAction(Request $request): Response
    {
        return $this->render('promotions/add_promotion.html.twig');
    }

    /**
     * @Route("/edit/promotion", name="editPromotion")
     * @param Request $request
     * @return Response
     */
    public function editPromotionAction(Request $request): Response
    {
        return $this->render(':promotions:edit_promotion.html.twig');
    }

    /**
     * @Route("/remove/promotion", name="removePromotion")
     * @param Request $request
     * @return Response
     */
    public function removePromotionAction(Request $request): Response
    {
        return $this->render(':promotions:list_promotions.html.twig');
    }
}
