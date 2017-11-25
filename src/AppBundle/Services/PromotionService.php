<?php

namespace AppBundle\Services;

use AppBundle\Entity\Categories;
use AppBundle\Entity\Promotion;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PromotionService implements IPromotionService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var ManagerRegistry
     */
    private $manager;

    /**
     * PromotionService constructor.
     * @param EntityManagerInterface $em
     * @param SessionInterface $session
     * @param ManagerRegistry $manager
     */
    public function __construct(EntityManagerInterface $em,
                                SessionInterface $session,
                                ManagerRegistry $manager)
    {
        $this->em = $em;
        $this->session = $session;
        $this->manager = $manager;
    }

    /**
     * @param Promotion $promotion
     * @param Categories $category
     * @return bool
     */
    public function applyPromotionForCategory(Promotion $promotion,
                                              Categories $category): bool
    {
        // TODO: Implement applyPromotionForCategory() method.
    }

    /**
     * @param Promotion $promotion
     * @param Categories $category
     * @return bool
     */
    public function removePromotionForCategory(Promotion $promotion,
                                               Categories $category): bool
    {
        // TODO: Implement removePromotionForCategory() method.
    }

    /**
     * @param Promotion $promotion
     * @return bool
     */
    public function applyPromotionForProducts(Promotion $promotion): bool
    {
        // TODO: Implement applyPromotionForProducts() method.
    }

    /**
     * @param Promotion $promotion
     * @return bool
     */
    public function removePromotionForProducts(Promotion $promotion): bool
    {
        // TODO: Implement removePromotionForProducts() method.
    }
}