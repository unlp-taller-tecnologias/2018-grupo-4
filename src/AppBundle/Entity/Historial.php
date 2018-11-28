<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
    * @ORM\ManyToOne(targetEntity="Transferencia", inversedBy="historial")
    * @ORM\JoinColumn(name="transferencia_id", referencedColumnName="id", nullable=false)
    */
    private $transferencia;
    /**
     * Get transferencia
     *
     * @return Transferencia
     */
    public function getTransferencia()
    {
        return $this->transferencia;
    }

    /**
     * Set transferencia
     *
     * @param string $transferencia
     * @return Transferencia
     */
    public function setTransferencia($transferencia)
    {
        $this->transferencia = $transferencia;
        return $this;
    }


    /**
     * @var Condicion
     *
     * @ORM\ManyToOne(targetEntity="Condicion")
     * @ORM\JoinColumn(name="condicion_id", referencedColumnName="id")
     */
    private $condicion;



    /**
     * Set condicion
     *
     * @param string $condicion
     *
     * @return Condicion
     */
    public function setCondicion($condicion)
    {
        $this->condicion = $condicion;

        return $this;
    }

    /**
     * Get condicion
     *
     * @return string
     */
    public function getCondicion()
    {
        return $this->condicion;
    }


    public function __construct()
    {

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
