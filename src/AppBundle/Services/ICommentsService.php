<?php

namespace AppBundle\Services;

use AppBundle\Entity\Comments;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;

interface ICommentsService
{
    /**
     * @param Comments $comments
     * @param Product $product
     * @param User $user
     * @return bool
     */
    public function addCommentOnProduct(Comments $comments,
                                        Product $product,
                                        User $user): bool;

    /**
     * @param Product $product
     * @return array
     */
    public function getCommentsOnProduct(Product $product): array;
}