<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * userShop
 *
 * @ORM\Table(name="user_shop")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\userShopRepository")
 */
class userShop
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
     * @ORM\ManyToOne(targetEntity="User" , inversedBy="usersShops")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;



    /**
     * @ORM\ManyToOne(targetEntity="Shop" , inversedBy="usersShops")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shop;



    /**
     * @var bool
     *
     * @ORM\Column(name="isliked", type="boolean",nullable=true)
     */
    private $isliked;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dislikedTime", type="datetime",nullable=true)
     */
    private $dislikedTime;

    /**
     * @var bool
     *
     * @ORM\Column(name="isfavorite", type="boolean",nullable=true)
     */
    private $isfavorite;


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
     * Set isliked
     *
     * @param boolean $isliked
     *
     * @return userShop
     */
    public function setIsliked($isliked)
    {
        $this->isliked = $isliked;

        return $this;
    }

    /**
     * Get isliked
     *
     * @return bool
     */
    public function getIsliked()
    {
        return $this->isliked;
    }

    /**
     * Set dislikedTime
     *
     * @param \DateTime $dislikedTime
     *
     * @return userShop
     */
    public function setDislikedTime($dislikedTime)
    {
        $this->dislikedTime = $dislikedTime;

        return $this;
    }

    /**
     * Get dislikedTime
     *
     * @return \DateTime
     */
    public function getDislikedTime()
    {
        return $this->dislikedTime;
    }

    /**
     * Set isfavorite
     *
     * @param boolean $isfavorite
     *
     * @return userShop
     */
    public function setIsfavorite($isfavorite)
    {
        $this->isfavorite = $isfavorite;

        return $this;
    }

    /**
     * Get isfavorite
     *
     * @return bool
     */
    public function getIsfavorite()
    {
        return $this->isfavorite;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * @param mixed $shop
     */
    public function setShop($shop)
    {
        $this->shop = $shop;
    }
}

