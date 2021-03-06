<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Shop
 *
 * @ORM\Table(name="shop")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShopRepository")
 */
class Shop
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="userShop", mappedBy="shop", fetch="EXTRA_LAZY")
     */
    protected $usersShops;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    private $country;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Shop
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Shop
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
     * @return Shop
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


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usersShops = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add usersShop
     *
     * @param \AppBundle\Entity\userShop $usersShop
     *
     * @return Shop
     */
    public function addUsersShop(\AppBundle\Entity\userShop $usersShop)
    {
        $this->usersShops[] = $usersShop;
        $usersShop->setShop($this);
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
     * @return Shop
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
     * @return Shop
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
}
