<?php

namespace AppBundle\Entity;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;


/**
*@ORM\Entity
*@ORM\Table(name="`user`")
*/
Class User extends BaseUser{

  /**
  *@ORM\Id
  *@ORM\GeneratedValue(strategy="AUTO")
  *@ORM\Column(type="integer")
  */
  protected $id;

  public function getId(){
    return $this->id;
  }

  public function getRoles(){
      return $this->roles;
  }




  





}
