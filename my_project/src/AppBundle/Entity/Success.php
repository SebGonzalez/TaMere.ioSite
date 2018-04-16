<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Success
 *
 * @ORM\Table(name="Success")
 * @ORM\Entity
 */
class Success
{
    /**
     * @var integer
     *
     * @ORM\Column(name="successId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $successId;

    /**
     * @var string
     *
     * @ORM\Column(name="successLabel", type="string", length=50, nullable=false)
     */
    private $successLabel;

    /**
     * @var string
     *
     * @ORM\Column(name="successDescription", type="text", length=65535, nullable=false)
     */
    private $successDescription;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userid = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set sucessLabel
     *
     * @param string $sucessLabel
     *
     * @return Success
     */
    public function setSuccessLabel($sucessLabel)
    {
        $this->sucessLabel = $sucessLabel;

        return $this;
    }

    /**
     * Get sucessLabel
     *
     * @return string
     */
    public function getSuccessLabel()
    {
        return $this->sucessLabel;
    }

    /**
     * Set successDescription
     *
     * @param string $successDescription
     *
     * @return Sucess
     */
    public function setSuccessDescription($successDescription)
    {
        $this->successDescription = $successDescription;

        return $this;
    }

    /**
     * Get successDescription
     *
     * @return string
     */
    public function getSuccessDescription()
    {
        return $this->successDescription;
    }

    /**
     * Get sucessId
     *
     * @return integer
     */
    public function getSuccessId()
    {
        return $this->sucessId;
    }

    /**
     * Add userId
     *
     * @param \AppBundle\Entity\User $userId
     *
     * @return Sucess
     */
    public function addUserId(\AppBundle\Entity\User $userId)
    {
        $this->userId[] = $userId;

        return $this;
    }

    /**
     * Remove userId
     *
     * @param \AppBundle\Entity\User $userId
     */
    public function removeUserId(\AppBundle\Entity\User $userId)
    {
        $this->userId->removeElement($userId);
    }

    /**
     * Get userId
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
