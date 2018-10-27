<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Oficina
 *
 * @ORM\Table(name="oficina")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OficinaRepository")
 */
class Oficina {
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var int
     *
     * @ORM\Column(name="num_carpeta", type="integer", unique=true)
     */
    private $numCarpeta;

    /**
     * @var string
     *
     * @ORM\Column(name="responsable", type="string", length=255, nullable=true)
     */
    private $responsable;


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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Oficina
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set numCarpeta
     *
     * @param integer $numCarpeta
     *
     * @return Oficina
     */
    public function setNumCarpeta($numCarpeta)
    {
        $this->numCarpeta = $numCarpeta;

        return $this;
    }

    /**
     * Get numCarpeta
     *
     * @return int
     */
    public function getNumCarpeta()
    {
        return $this->numCarpeta;
    }

    /**
     * Set responsable
     *
     * @param string $responsable
     *
     * @return Oficina
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return string
     */
    public function getResponsable()
    {
        return $this->responsable;
    }
}
