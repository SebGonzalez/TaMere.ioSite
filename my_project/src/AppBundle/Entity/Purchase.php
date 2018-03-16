<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Purchase
 *
 * @ORM\Table(name="Purchase", indexes={@ORM\Index(name="FK_Purchase_objectId", columns={"objectId"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PurchaseRepository")
 */
class Purchase
{
    /**
    * @var \AppBundle\Entity\User
    *
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="NONE")
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
    * @ORM\JoinColumns({
    * @ORM\JoinColumn(name="userId", referencedColumnName="userId")
    *})
    */
    private $user;

    /**
    * @var \AppBundle\Entity\Object
    *
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="NONE")
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Object")
    * @ORM\JoinColumns({
    * @ORM\JoinColumn(name="objectId", referencedColumnName="objectId")
    *})
    */
    private $object;

    /**
    *@var float
    *
    *@ORM\Column(name="purchasePrice", type="float", precision=10, scale=0, nullable=false)
    */
    private $purchasePrice;

    /**
    * @var \DateTime
    *
    *@ORM\Column(name="purchaseDate", type="date", nullable=false)
    */
    private $purchaseDate;

    /**
     * Set purchasePrice
     *
     * @param float $purchasePrice
     *
     * @return Purchase
     */
    public function setPurchasePrice($purchasePrice)
    {
        $this->purchasePrice = $purchasePrice;

        return $this;
    }

    /**
     * Get purchasePrice
     *
     * @return float
     */
    public function getPurchasePrice()
    {
        return $this->purchasePrice;
    }

    /**
     * Set purchaseDate
     *
     * @param \DateTime $purchaseDate
     *
     * @return Purchase
     */
    public function setPurchaseDate($purchaseDate)
    {
        $this->purchaseDate = $purchaseDate;

        return $this;
    }

    /**
     * Get purchaseDate
     *
     * @return \DateTime
     */
    public function getPurchaseDate()
    {
        return $this->purchaseDate;
    }

    /**
     * Set object
     *
     * @param \AppBundle\Entity\Object $object
     *
     * @return Purchase
     */
    public function setObject(\AppBundle\Entity\Object $object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Get object
     *
     * @return \AppBundle\Entity\Object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Purchase
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
