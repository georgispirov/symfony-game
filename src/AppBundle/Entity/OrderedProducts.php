<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderedProducts
 *
 * @ORM\Table(name="ordered_products")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderedProductsRepository")
 */
class OrderedProducts
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ordered_date", type="datetime")
     */
    private $orderedDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="confirmed", type="boolean")
     */
    private $confirmed;

    /**
     * @var float
     *
     * @ORM\Column(name="totalCheck", type="float")
     */
    private $totalCheck;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="ordered_products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Set orderedDate
     *
     * @param \DateTime $orderedDate
     *
     * @return OrderedProducts
     */
    public function setOrderedDate($orderedDate)
    {
        $this->orderedDate = $orderedDate;

        return $this;
    }

    /**
     * Get orderedDate
     *
     * @return \DateTime
     */
    public function getOrderedDate()
    {
        return $this->orderedDate;
    }

    /**
     * Set confirmed
     *
     * @param boolean $confirmed
     *
     * @return OrderedProducts
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    /**
     * Get confirmed
     *
     * @return bool
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * Set totalCheck
     *
     * @param float $totalCheck
     *
     * @return OrderedProducts
     */
    public function setTotalCheck($totalCheck)
    {
        $this->totalCheck = $totalCheck;

        return $this;
    }

    /**
     * Get totalCheck
     *
     * @return float
     */
    public function getTotalCheck()
    {
        return $this->totalCheck;
    }
}

