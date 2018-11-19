<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Historial
 *
 * @ORM\Table(name="historial")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HistorialRepository")
 */
class Historial
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;


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
     * Set fecha.
     *
     * @param \DateTime $fecha
     *
     * @return Historial
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha.
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }



    /**
     * @ORM\ManyToOne(targetEntity="Transferencia", inversedBy="historiales")
     */
    private $transferencia;

    public function __construct()
    {
        $this->transferencia = new ArrayCollection();
    }

    public function __toString() {
        return $this->nombre;
    }

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Articulo")
     * @ORM\JoinColumn(name="articulo_id", referencedColumnName="id")
     */
    private $articulo;

    /**
     * Get articulo
     *
     * @return Articulo
     */
    public function getArticulo()
    {
        return $this->articulo;
    }

    /**
     * Set articulo
     *
     * @param string $articulo
     * @return Articulo
     */
    public function setArticulo($articulo)
    {
        $this->articulo = $articulo;

        return $this;
    }



}
