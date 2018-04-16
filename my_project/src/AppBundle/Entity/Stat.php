<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stat
 *
 * @ORM\Table(name="Stat", indexes={@ORM\Index(name="FK_Stat_weaponId", columns={"weaponId"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StatRepository")
 */
class Stat
{
    /**
     * @var string
     *
     * @ORM\Column(name="statNbKill", type="bigint", nullable=false)
     */
    private $statNbKill;

    /**
     * @var integer
     *
     * @ORM\Column(name="statBestLevel", type="integer", nullable=false)
     */
    private $statBestLevel;

    /**
     * @var integer
     *
     * @ORM\Column(name="statNbGames", type="integer", nullable=false)
     */
    private $statNbGames;

    /**
     * @var integer
     *
     * @ORM\Column(name="statNbThrowed", type="integer", nullable=false)
     */
    private $statNbThrowed;

    /**
     * @var integer
     *
     * @ORM\Column(name="statNbTouched", type="integer", nullable=false)
     */
    private $statNbTouched;

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
    private $user;

    /**
     * @var \AppBundle\Entity\Object
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Object")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="weaponId", referencedColumnName="objectId")
     * })
     */
    private $object;

    /**
     * Set statNbKill
     *
     * @param integer $statNbKill
     *
     * @return Stat
     */
    public function setStatNbKill($statNbKill)
    {
        $this->statNbKill = $statNbKill;

        return $this;
    }

    /**
     * Get statNbKill
     *
     * @return integer
     */
    public function getStatNbKill()
    {
        return $this->statNbKill;
    }

    /**
     * Set statBestLevel
     *
     * @param integer $statBestLevel
     *
     * @return Stat
     */
    public function setStatBestLevel($statBestLevel)
    {
        $this->statBestLevel = $statBestLevel;

        return $this;
    }

    /**
     * Get statBestLevel
     *
     * @return integer
     */
    public function getStatBestLevel()
    {
        return $this->statBestLevel;
    }

    /**
     * Set statNbGames
     *
     * @param integer $statNbGames
     *
     * @return Stat
     */
    public function setStatNbGames($statNbGames)
    {
        $this->statNbGames = $statNbGames;

        return $this;
    }

    /**
     * Get statNbGames
     *
     * @return integer
     */
    public function getStatNbGames()
    {
        return $this->statNbGames;
    }

    /**
     * Set statNbThrowed
     *
     * @param integer $statNbThrowed
     *
     * @return Stat
     */
    public function setStatNbThrowed($statNbThrowed)
    {
        $this->statNbThrowed = $statNbThrowed;

        return $this;
    }

    /**
     * Get statNbThrowed
     *
     * @return integer
     */
    public function getStatNbThrowed()
    {
        return $this->statNbThrowed;
    }

    /**
     * Set statNbTouched
     *
     * @param integer $statNbTouched
     *
     * @return Stat
     */
    public function setStatNbTouched($statNbTouched)
    {
        $this->statNbTouched = $statNbTouched;

        return $this;
    }

    /**
     * Get statNbTouched
     *
     * @return integer
     */
    public function getStatNbTouched()
    {
        return $this->statNbTouched;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Stat
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

    /**
     * Set object
     *
     * @param \AppBundle\Entity\Object $object
     *
     * @return Stat
     */
    public function setObject(\AppBundle\Entity\Weapon $object)
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
}
