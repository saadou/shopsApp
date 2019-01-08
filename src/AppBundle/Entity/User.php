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

    public function __construct()
    {
        parent::__construct();
        // your own logic
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



}
