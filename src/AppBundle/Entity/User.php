<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @UniqueEntity(
 *   fields={"username"},
 *   message="El nombre de usuario ya existe."
 * )
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
     * @ORM\Column(type="string", length=255, options={"default":"name"}) )
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

     /**
      * @ORM\Column(type="string", length=255, options={"default":"name"})  )
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
      protected $username;
      protected $username_canonical;
      protected $email;
      protected $email_canonical;
      protected $enabled;
      protected $salt;
      protected $password;
      protected $last_login;
      protected $confirmation_token;
      protected $password_requested_at;
      protected $roles;

      public function getLastname(){
        return $this->lastname;
      }

      public function setLastname($l){
        $this->lastname=$l;
      }

      public function getName(){
        return $this->name;
      }

      public function getEnabled(){
        return $this->enabled;
      }
      public function setEnabled($n){
        $this->enabled=$n;
      }

      public function setName($n){
        $this->name=$n;
      }

      public function getUsername(){
        return $this->username;
      }

      public function setUsername($n){
        $this->username=$n;
        $this->email = $n;
        $this->enabled = true;
      }

      public function getEmail(){
        return $this->email;
      }

      public function setEmail($n){
        $this->email=$n;
      }

      public function getPassword(){
        return $this->password;
      }

      public function setPassword($n){
        $this->password=$n;
      }

      public function __construct() {
          $this->enabled = true;
      }

  }
