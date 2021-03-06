<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * estadoAdicional
 *
 * @ORM\Table(name="estado_adicional")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\estadoAdicionalRepository")
 * @UniqueEntity(
 *   fields={"nombre"},
 *   message="El nombre de estado ya existe."
 * )
 * @UniqueEntity(
 *   fields={"color"},
 *   message="El color ya existe."
 * )
 */
class estadoAdicional
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, unique=true)
     */
    private $nombre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="color", type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @var bool
     *
     * @ORM\Column(name="habilitado", type="boolean")
     */
    private $habilitado;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
     */
    private $descripcion;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre.
     *
     * @param string $nombre
     *
     * @return estadoAdicional
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre.
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set color.
     *
     * @param string|null $color
     *
     * @return estadoAdicional
     */
    public function setColor($color = null)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color.
     *
     * @return string|null
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set habilitado.
     *
     * @param int $habilitado
     *
     * @return estadoAdicional
     */
    public function setHabilitado($habilitado)
    {
        $this->habilitado = $habilitado;

        return $this;
    }

    /**
     * Get habilitado.
     *
     * @return int
     */
    public function getHabilitado()
    {
        return $this->habilitado;
    }

    /**
     * Set descripcion.
     *
     * @param string $descripcion
     *
     * @return estadoAdicional
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion.
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function __toString() {
        return $this->nombre;
    }

    public function __construct() {
        $this->habilitado = true;;
    }
}
