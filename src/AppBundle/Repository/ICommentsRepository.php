<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Comments;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Doctrine\ORM\QueryBuilder;

interface ICommentsRepository
{
    /**
     * @return QueryBuilder
     */
    public function getCommentsOnCertainUser() : QueryBuilder;

    /**
     * @param Product $product
     * @return array
     */
    public function getCommentsOnCertainProduct(Product $product): array;

    /**
     * @param Comments $comments
     * @param Product $product
     * @param User $user
     * @return bool
     */
    public function addCommentOnProduct(Comments $comments,
                                        Product $product,
                                        User $user): bool;
}