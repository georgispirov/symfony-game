<?php

namespace AppBundle\Entity;

use APY\DataGridBundle\Grid\Mapping\Column;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(name="ordered_date", type="datetime")
     */
    private $orderedDate;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="id")
     */
    private $product;

    /**
     * @var integer
     *
     * @ORM\Column(name="confirmed", type="integer")
     */
    private $confirmed;

    /**
     * @ORM\Column(name="quantity", type="integer")
     * @Assert\NotBlank(message="Quantity cannot be blank.")
     * @Assert\GreaterThanOrEqual(value="1", message="Quantity must be at least 1.")
     */
    private $quantity;

    /**
     * @var float
     * @ORM\Column(name="ordered_product_price", type="float")
     */
    private $orderedProductPrice;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="id")
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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param null|User $user
     * @return $this
     */
    public function setUser($user)
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
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @return float
     */
    public function getOrderedProductPrice(): float
    {
        return $this->orderedProductPrice;
    }

    /**
     * @param float $orderedProductPrice
     */
    public function setOrderedProductPrice(float $orderedProductPrice)
    {
        $this->orderedProductPrice = $orderedProductPrice;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }
}

