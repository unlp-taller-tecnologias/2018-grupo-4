<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Please enter your name.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
     protected $name;

     public function getName(){
       return $this->name;
     }

     public function setName($n){
       $this->name=$n;
     }

     /**
      * @ORM\Column(type="string", length=255)
      *
      * @Assert\NotBlank(message="Please enter your lastname.", groups={"Registration", "Profile"})
      * @Assert\Length(
      *     min=3,
      *     max=255,
      *     minMessage="The lastname is too short.",
      *     maxMessage="The lastname is too long.",
      *     groups={"Registration", "Profile"}
      * )
      */
      protected $lastname;

      public function getLastname(){
        return $this->lastname;
      }

      public function setLastname($l){
        $this->lastname=$l;
      }

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}
