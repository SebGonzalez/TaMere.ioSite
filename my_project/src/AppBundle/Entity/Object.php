<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Object
 *
 * @ORM\Table(name="Object", indexes={@ORM\Index(name="FK_Object_objectCategoryId", columns={"objectCategoryId"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ObjectRepository")
 */
class Object
{
    /**
     * @var integer
     *
     * @ORM\Column(name="objectId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $objectId;

    /**
     * @var string
     *
     * @ORM\Column(name="objectLabel", type="string", length=50, nullable=false)
     */
    private $objectLabel;

    /**
     * @var string
     *
     * @ORM\Column(name="objectDescription", type="text", length=65535, nullable=false)
     */
    private $objectDescription;

    /**
     * @var float
     *
     * @ORM\Column(name="objectPrice", type="float", precision=10, scale=0, nullable=false)
     */
    private $objectPrice;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="objectUploadDate", type="date", nullable=false)
     */
    private $objectUploadDate;

    /**
    * @var string
    *
    *@ORM\Column(name="objectPicturePath", type="string", length=100, nullable=true)
    */
    private $objectPicturePath;

    /**
     * @var \AppBundle\Entity\ObjectCategory
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ObjectCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="objectCategoryId", referencedColumnName="objectCategoryId", nullable=false)
     * })
     */
    private $objectCategoryId;

    /**
     * Get objectid
     *
     * @return integer
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Set objectlabel
     *
     * @param string $objectlabel
     *
     * @return Object
     */
    public function setObjectLabel($objectlabel)
    {
        $this->objectLabel = $objectLabel;

        return $this;
    }

    /**
     * Get objectlabel
     *
     * @return string
     */
    public function getObjectLabel()
    {
        return $this->objectLabel;
    }

    /**
     * Set objectprice
     *
     * @param float $objectprice
     *
     * @return Object
     */
    public function setObjectPrice($objectPrice)
    {
        $this->objectPrice = $objectPrice;

        return $this;
    }

    /**
     * Get objectprice
     *
     * @return float
     */
    public function getObjectPrice()
    {
        return $this->objectPrice;
    }

    /**
     * Set objectuploaddate
     *
     * @param \DateTime $objectUploadDate
     *
     * @return Object
     */
    public function setObjectUploadDate($objectuploaddate)
    {
        $this->objectUploadDate = $objectUploadDate;

        return $this;
    }

    /**
     * Get objectUploadDate
     *
     * @return \DateTime
     */
    public function getObjectUploadDate()
    {
        return $this->objectUploadDate;
    }

    /**
     * Set objectdescription
     *
     * @param string $objectdescription
     *
     * @return Object
     */
    public function setObjectdescription($objectdescription)
    {
        $this->objectdescription = $objectdescription;

        return $this;
    }

    /**
     * Get objectdescription
     *
     * @return string
     */
    public function getObjectdescription()
    {
        return $this->objectdescription;
    }

    /**
     * Set objectcategoryid
     *
     * @param \AppBundle\Entity\ObjectCategory $objectcategoryid
     *
     * @return Object
     */
    public function setObjectCategoryId(\AppBundle\Entity\ObjectCategory $objectCategoryId = null)
    {
        $this->objectCategoryId = $objectCategoryId;

        return $this;
    }

    /**
     * Get objectCategoryId
     *
     * @return \AppBundle\Entity\ObjectCategory
     */
    public function getObjectCategoryId()
    {
        return $this->objectCategoryId;
    }

    /**
     * Set objectPicturePath
     *
     * @param string $objectPicturePath
     *
     * @return Object
     */
    public function setObjectPicturePath($objectPicturePath)
    {
        $this->objectPicturePath = $objectPicturePath;

        return $this;
    }

    /**
     * Get objectPicturePath
     *
     * @return string
     */
    public function getObjectPicturePath()
    {
        return $this->objectPicturePath;
    }
}
