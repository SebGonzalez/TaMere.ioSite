<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Personalization
 *
 * @ORM\Table(name="Personalization", indexes={@ORM\Index(name="FK_Personalization_objectCategoryId", columns={"objectCategoryId"}), @ORM\Index(name="FK_Personalization_objectId", columns={"objectId"}), @ORM\Index(name="IDX_E3E76F4B64B64DCC", columns={"userId"})})
 * @ORM\Entity
 */
class Personalization
{
    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="userId", referencedColumnName="userId")
     * })
     */
    private $userId;

    /**
     * @var \AppBundle\Entity\ObjectCategory
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ObjectCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="objectCategoryId", referencedColumnName="objectCategoryId")
     * })
     */
    private $objectCategoryId;

    /**
     * @var \AppBundle\Entity\Object
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Object")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="objectId", referencedColumnName="objectId", nullable=true)
     * })
     */
    private $objectId;



    /**
     * Set objectCategoryId
     *
     * @param \AppBundle\Entity\Objectcategory $objectCategoryId
     *
     * @return Personalization
     */
    public function setObjectCategoryId(\AppBundle\Entity\ObjectCategory $objectCategoryId)
    {
        $this->objectCategoryId = $objectCategoryId;

        return $this;
    }

    /**
     * Get objectCategoryId
     *
     * @return \AppBundle\Entity\Objectcategory
     */
    public function getObjectCategoryId()
    {
        return $this->objectCategoryId;
    }

    /**
     * Set userid
     *
     * @param \AppBundle\Entity\User $userId
     *
     * @return Personalization
     */
    public function setUserId(\AppBundle\Entity\User $userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return \AppBundle\Entity\User
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set objectid
     *
     * @param \AppBundle\Entity\Object $objectId
     *
     * @return Personalization
     */
    public function setObjectId(\AppBundle\Entity\Object $objectId = null)
    {
        $this->objectId = $objectId;

        return $this;
    }

    /**
     * Get objectid
     *
     * @return \AppBundle\Entity\Object
     */
    public function getObjectId()
    {
        return $this->objectId;
    }
}
