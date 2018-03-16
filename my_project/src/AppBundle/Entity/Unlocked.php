<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Unlocked
 *
 * @ORM\Table(name="unlocked", indexes={@ORM\Index(name="FK_unlocked_sucessId", columns={"successId"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UnlockedRepository")
 */
class Unlocked
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
    * @var \AppBundle\Entity\Success
    *
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="NONE")
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Success")
    * @ORM\JoinColumns({
    * @ORM\JoinColumn(name="successId", referencedColumnName="successId")
    *})
    */
    private $success;
}

