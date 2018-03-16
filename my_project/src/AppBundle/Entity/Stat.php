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
     * @var \AppBundle\Entity\Weapon
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Weapon")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="weaponId", referencedColumnName="weaponId")
     * })
     */
    private $weapon;

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
     * Set weapon
     *
     * @param \AppBundle\Entity\Weapon $weapon
     *
     * @return Stat
     */
    public function setWeapon(\AppBundle\Entity\Weapon $weapon)
    {
        $this->weapon = $weapon;

        return $this;
    }

    /**
     * Get weapon
     *
     * @return \AppBundle\Entity\Weapon
     */
    public function getWeapon()
    {
        return $this->weapon;
    }
}
