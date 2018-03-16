<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Weapon
 *
 * @ORM\Table(name="Weapon")
 * @ORM\Entity
 */
class Weapon
{
    /**
     * @var integer
     *
     * @ORM\Column(name="weaponId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $weaponId;

    /**
     * @var string
     *
     * @ORM\Column(name="weaponName", type="string", length=30, nullable=false)
     */
    private $weaponName;

    /**
     * Get weaponId
     *
     * @return integer
     */
    public function getWeaponId()
    {
        return $this->weaponId;
    }

    /**
     * Set weaponName
     *
     * @param string $weaponName
     *
     * @return Weapon
     */
    public function setWeaponName($weaponName)
    {
        $this->weaponName = $weaponName;

        return $this;
    }

    /**
     * Get weaponName
     *
     * @return string
     */
    public function getWeaponName()
    {
        return $this->weaponName;
    }
}
