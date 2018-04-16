<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Objectcategory
 *
 * @ORM\Table(name="ObjectCategory")
 * @ORM\Entity
 */
class ObjectCategory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="objectCategoryId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $objectCategoryId;

    /**
     * @var string
     *
     * @ORM\Column(name="ObjectCategoryLabel", type="string", length=50, nullable=false)
     */
    private $objectCategoryLabel;

    /**
     * Get objectCategoryId
     *
     * @return integer
     */
    public function getObjectCategoryId()
    {
        return $this->objectCategoryId;
    }

    /**
     * Set objectCategoryLabel
     *
     * @param string $objectCategoryLabel
     *
     * @return ObjectCategory
     */
    public function setObjectCategoryLabel($objectCategoryLabel)
    {
        $this->objectCategoryLabel = $objectCategoryLabel;

        return $this;
    }

    /**
     * Get objectCategoryLabel
     *
     * @return string
     */
    public function getObjectCategoryLabel()
    {
        return $this->objectCategoryLabel;
    }
}
