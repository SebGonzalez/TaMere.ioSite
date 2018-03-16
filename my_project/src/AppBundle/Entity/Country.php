<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="Country")
 * @ORM\Entity
 */
class Country
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idCountry", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="countryName", type="string", length=50, nullable=false)
     */
    private $countryName;

    /**
     * Get idcountry
     *
     * @return integer
     */
    public function getIdcountry()
    {
        return $this->idCountry;
    }

    /**
     * Set countryname
     *
     * @param string $countryname
     *
     * @return Country
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;

        return $this;
    }

    /**
     * Get countryname
     *
     * @return string
     */
    public function getCountryName()
    {
        return $this->countryName;
    }
}
