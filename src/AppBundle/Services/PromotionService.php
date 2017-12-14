<?php

namespace AppBundle\Services;

use AppBundle\Entity\Categories;
use AppBundle\Entity\Product;
use AppBundle\Entity\Promotion;
use AppBundle\Repository\PromotionRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Process\Exception\InvalidArgumentException;

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

    }

    /**
     * @param Promotion $promotion
     * @param Categories $category
     * @return bool
     */
    public function removePromotionForCategory(Promotion $promotion,
                                               Categories $category): bool
    {

    }

    /**
     * @param Promotion $promotion
     * @return bool
     */
    public function applyPromotionForProducts(Promotion $promotion): bool
    {
        if ( !$promotion instanceof Promotion ) {
            throw new InvalidArgumentException('Applied argument must be valid Promotion Entity!');
        }

        return $this->em->getRepository(Promotion::class)
                        ->addPromotionForProducts($promotion);
    }

    /**
     * @param Promotion $promotion
     * @return bool
     */
    public function removePromotionForProducts(Promotion $promotion): bool
    {

    }

    /**
     * @return array
     */
    public function getAllPromotions(): array
    {
        return $this->em->getRepository(Promotion::class)
                        ->getActivePromotions();
    }

    /**
     * @return array
     */
    public function getAllActivePromotion(): array
    {
        // TODO: Implement getAllActivePromotion() method.
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getAllPromotionsByDateInterval(string $startDate, string $endDate): array
    {
        // TODO: Implement getAllPromotionsByDateInterval() method.
    }

    /**
     * @param Categories $category
     * @return array
     */
    public function getAllPromotionsByCategory(Categories $category): array
    {
        // TODO: Implement getAllPromotionsByCategory() method.
    }

    /**
     * @param Product $product
     * @return array
     */
    public function getAllPromotionsByProduct(Product $product): array
    {
        // TODO: Implement getAllPromotionsByProduct() method.
    }

    /**
     * @param Promotion $promotion
     * @return array
     */
    public function getNonExistingProductsInPromotion(Promotion $promotion): array
    {
        if ( !$promotion instanceof Promotion ) {
            throw new InvalidArgumentException('Promotion must be a valid entity!');
        }

        return $this->em->getRepository(Product::class)
                        ->getNonExistingProductsInPromotion($promotion);
    }
}