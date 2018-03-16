<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="User", uniqueConstraints={@ORM\UniqueConstraint(name="userMail", columns={"userMail", "userLogin"})}, indexes={@ORM\Index(name="FK_User_idCountry", columns={"idCountry"})})
 * @ORM\Entity
 */
class User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="userId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="userFirstName", type="string", length=30, nullable=false)
     */
    private $userFirstName;

    /**
     * @var string
     *
     * @ORM\Column(name="userLastName", type="string", length=30, nullable=false)
     */
    private $userLastName;

    /**
     * @var string
     *
     * @ORM\Column(name="userMail", type="string", length=50, nullable=false)
     */
    private $userMail;

    /**
     * @var string
     *
     * @ORM\Column(name="userLogin", type="string", length=30, nullable=false)
     */
    private $userLogin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="userBirthday", type="date", nullable=false)
     */
    private $userBirthday;

    /**
     * @var boolean
     *
     * @ORM\Column(name="userGender", type="boolean", nullable=false)
     */
    private $userGender;

    /**
     * @var string
     *
     * @ORM\Column(name="userPassword", type="string", length=64, nullable=false)
     */
    private $userPassword;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="userSubscriptionDate", type="date", nullable=false)
     */
    private $userSubscriptionDate;

    

    /**
     * @var \AppBundle\Entity\Country
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idCountry", referencedColumnName="idCountry", nullable=false)
     * })
     */
    private $idCountry;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->successId = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set userFirstName
     *
     * @param string $userFirstName
     *
     * @return User
     */
    public function setUserFirstName($userFirstName)
    {
        $this->userFirstName = $userFirstName;

        return $this;
    }

    /**
     * Get userFirstName
     *
     * @return string
     */
    public function getUserFirstName()
    {
        return $this->userFirstName;
    }

    /**
     * Set userLastName
     *
     * @param string $userLastName
     *
     * @return User
     */
    public function setUserLastName($userLastName)
    {
        $this->userLastName = $userLastName;

        return $this;
    }

    /**
     * Get userLastName
     *
     * @return string
     */
    public function getUserLastName()
    {
        return $this->userLastName;
    }

    /**
     * Set userMail
     *
     * @param string $userMail
     *
     * @return User
     */
    public function setUserMail($userMail)
    {
        $this->userMail = $userMail;

        return $this;
    }

    /**
     * Get userMail
     *
     * @return string
     */
    public function getUserMail()
    {
        return $this->userMail;
    }

    /**
     * Set userLogin
     *
     * @param string $userLogin
     *
     * @return User
     */
    public function setUserLogin($userLogin)
    {
        $this->userLogin = $userLogin;

        return $this;
    }

    /**
     * Get userLogin
     *
     * @return string
     */
    public function getUserLogin()
    {
        return $this->userLogin;
    }

    /**
     * Set userBirthday
     *
     * @param \DateTime $userBirthday
     *
     * @return User
     */
    public function setUserBirthday($userBirthday)
    {
        $this->userBirthday = $userBirthday;

        return $this;
    }

    /**
     * Get userBirthday
     *
     * @return \DateTime
     */
    public function getUserBirthday()
    {
        return $this->userBirthday;
    }

    /**
     * Set userGender
     *
     * @param boolean $userGender
     *
     * @return User
     */
    public function setUserGender($userGender)
    {
        $this->userGender = $userGender;

        return $this;
    }

    /**
     * Get userGender
     *
     * @return boolean
     */
    public function getUserGender()
    {
        return $this->userGender;
    }

    /**
     * Set userPassword
     *
     * @param string $userPassword
     *
     * @return User
     */
    public function setUserPassword($userPassword)
    {
        $this->userPassword = $userPassword;

        return $this;
    }

    /**
     * Get userPassword
     *
     * @return string
     */
    public function getUserPassword()
    {
        return $this->userPassword;
    }

    /**
     * Set userSubscriptionDate
     *
     * @param \DateTime $userSubscriptionDate
     *
     * @return User
     */
    public function setUserSubscriptionDate($userSubscriptionDate)
    {
        $this->userSubscriptionDate = $userSubscriptionDate;

        return $this;
    }

    /**
     * Get userSubscriptionDate
     *
     * @return \DateTime
     */
    public function getUserSubscriptionDate()
    {
        return $this->userSubscriptionDate;
    }

    /**
     * Set idCountry
     *
     * @param \AppBundle\Entity\Country $idCountry
     *
     * @return User
     */
    public function setIdCountry(\AppBundle\Entity\Country $idCountry = null)
    {
        $this->idCountry = $idCountry;

        return $this;
    }

    /**
     * Get idCountry
     *
     * @return \AppBundle\Entity\Country
     */
    public function getIdCountry()
    {
        return $this->idCountry;
    }

    /**
     * Add sucessId
     *
     * @param \AppBundle\Entity\Sucess $sucessId
     *
     * @return User
     */
    public function addSuccessId(\AppBundle\Entity\Success $successId)
    {
        $this->successId[] = $successId;

        return $this;
    }

    /**
     * Remove sucessId
     *
     * @param \AppBundle\Entity\Sucess $sucessId
     */
    public function removeSuccessId(\AppBundle\Entity\Success $successId)
    {
        $this->successId->removeElement($successId);
    }

    /**
     * Get sucessId
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSuccessId()
    {
        return $this->successId;
    }
}
