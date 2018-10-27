<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * rol_permiso
 *
 * @ORM\Table(name="rol_permiso")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\rol_permisoRepository")
 */
class rol_permiso
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
     * @var int
     *
     * @ORM\Column(name="id_rol", type="integer")
     */
    private $idRol;

    /**
     * @var int
     *
     * @ORM\Column(name="id_permiso", type="integer")
     */
    private $idPermiso;


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
     * Set idRol
     *
     * @param integer $idRol
     *
     * @return rol_permiso
     */
    public function setIdRol($idRol)
    {
        $this->idRol = $idRol;

        return $this;
    }

    /**
     * Get idRol
     *
     * @return int
     */
    public function getIdRol()
    {
        return $this->idRol;
    }

    /**
     * Set idPermiso
     *
     * @param integer $idPermiso
     *
     * @return rol_permiso
     */
    public function setIdPermiso($idPermiso)
    {
        $this->idPermiso = $idPermiso;

        return $this;
    }

    /**
     * Get idPermiso
     *
     * @return int
     */
    public function getIdPermiso()
    {
        return $this->idPermiso;
    }
}

