<?php

namespace AppBundle\Services;

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
}