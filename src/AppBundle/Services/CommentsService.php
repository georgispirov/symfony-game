<?php

namespace AppBundle\Services;

use AppBundle\Entity\Comments;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CommentsService implements ICommentsService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * CommentsService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Comments $comments
     * @param Product $product
     * @param User $user
     * @return bool
     */
    public function addCommentOnProduct(Comments $comments,
                                        Product $product,
                                        User $user): bool
    {
        return $this->em->getRepository(Comments::class)
                        ->addCommentOnProduct($comments, $product, $user);
    }

    /**
     * @param Product $product
     * @return array
     */
    public function getCommentsOnProduct(Product $product): array
    {
        return $this->em->getRepository(Comments::class)
                        ->getCommentsOnCertainProduct($product);
    }
}