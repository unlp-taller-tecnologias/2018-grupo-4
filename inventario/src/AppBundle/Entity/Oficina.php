<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Oficina
 *
 * @ORM\Table(name="oficinas")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OficinaRepository")
 * @UniqueEntity(
*     fields={"carpeta"},
*     message="El número de carpeta ya existe."
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
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="responsable", type="string", length=255, nullable=true)
     */
    private $responsable;

    /**
     * @ORM\OneToMany(targetEntity="Articulo", mappedBy="oficina")
     */
    private $articulos;

    /**
     * @var integer
     *
     * @ORM\Column(name="carpeta", type="integer", unique=true)
      * @Assert\Range(
      *      min = 1,
      *      minMessage = "El mínimo debe ser mayor o igual {{ limit }}",
      * )
     **/
    private $carpeta;

    public function __construct()
    {
        $this->acticulos = new ArrayCollection();
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

    /**
     * Set carpeta
     *
     * @param string $carpeta
     *
     * @return Oficina
     */
    public function setCarpeta($carpeta)
    {
        $this->carpeta = $carpeta;

        return $this;
    }

    /**
     * Get carpeta
     *
     * @return string
     */
    public function getCarpeta()
    {
        return $this->carpeta;
    }
}
