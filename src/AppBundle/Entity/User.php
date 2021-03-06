<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\Column(name="lat", type="float", nullable=true)
     */
    private $lat;

    /**
     * @var int
     *
     * @ORM\Column(name="longt", type="float", nullable=true)
     */
    private $longt;
    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    private $country;
    /**
     * @ORM\OneToMany(targetEntity="userShop", mappedBy="user", fetch="EXTRA_LAZY")
     */
    protected $usersShops;

    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->usersShops = new \Doctrine\Common\Collections\ArrayCollection();
        //$this->niveaux = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /*
     * Many Users have Many groupes/niveaux.
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Groupe", inversedBy="users")
     * @ORM\JoinTable(name="users_niveaux")

    private $niveaux;
    */

    /*
     *
     * add this in func add niveau
     *
     * if(!$this->getNiveaux()->contains($niveau)){
            $this->niveaux[] = $niveau;
            //i added this
            $niveau->addUser($this);
        }
    *
     */

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }




    /**
     * Add usersShop
     *
     * @param \AppBundle\Entity\userShop $usersShop
     *
     * @return User
     */
    public function addUsersShop(\AppBundle\Entity\userShop $usersShop)
    {
        $this->usersShops[] = $usersShop;
        $usersShop->setUser($this);
        return $this;
    }

    /**
     * Remove usersShop
     *
     * @param \AppBundle\Entity\userShop $usersShop
     */
    public function removeUsersShop(\AppBundle\Entity\userShop $usersShop)
    {
        $this->usersShops->removeElement($usersShop);
    }

    /**
     * Get usersShops
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsersShops()
    {
        return $this->usersShops;
    }

    /**
     * Set lat
     *
     * @param float $lat
     *
     * @return User
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set longt
     *
     * @param float $longt
     *
     * @return User
     */
    public function setLongt($longt)
    {
        $this->longt = $longt;

        return $this;
    }

    /**
     * Get longt
     *
     * @return float
     */
    public function getLongt()
    {
        return $this->longt;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }
}
