<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Oficina
 *
 * @ORM\Table(name="oficina")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OficinaRepository")
 * @UniqueEntity(
*   fields={"numeroCarpeta"},
*   message="El número de carpeta ya existe."
* )
* @UniqueEntity(
*   fields={"nombre"},
*   message="El nombre de oficina ya existe."
* )
 */

class Oficina
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
     * @ORM\Column(name="responsable_oficina", type="string", length=100, nullable=true)
     */
    private $responsableOficina;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100, unique=true)
     */
    private $nombre;

    /**
     * @var int
     *
     * @ORM\Column(name="numero_carpeta", type="integer", unique=true)
     * @Assert\Range(
    *   min = 1,
    *   minMessage = "El mínimo debe ser mayor o igual {{ limit }}",
    * )
     */
    private $numeroCarpeta;

    public function __construct()
    {
        $this->articulos = new ArrayCollection();
    }

    public function __toString() {
        return $this->nombre;
    }

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
     * Set responsableOficina
     *
     * @param string $responsableOficina
     *
     * @return Oficina
     */
    public function setResponsableOficina($responsableOficina)
    {
        $this->responsableOficina = $responsableOficina;

        return $this;
    }

    /**
     * Get responsableOficina
     *
     * @return string
     */
    public function getResponsableOficina()
    {
        return $this->responsableOficina;
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
     * Set numeroCarpeta
     *
     * @param integer $numeroCarpeta
     *
     * @return Oficina
     */
    public function setNumeroCarpeta($numeroCarpeta)
    {
        $this->numeroCarpeta = $numeroCarpeta;

        return $this;
    }

    /**
     * Get numeroCarpeta
     *
     * @return int
     */
    public function getNumeroCarpeta()
    {
        return $this->numeroCarpeta;
    }

}

