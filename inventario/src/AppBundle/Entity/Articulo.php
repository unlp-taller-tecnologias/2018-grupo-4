<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Articulo
 *
 * @ORM\Table(name="articulos")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticuloRepository")
 */
class Articulo
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
     * @ORM\Column(name="denominacion", type="string", length=100)
     */
    private $denominacion;

    /**
     * @var int
     *
     * @ORM\Column(name="numero_inventario", type="integer")
     */
    private $numeroInventario;

    /**
     * @ORM\ManyToOne(targetEntity="Oficina", inversedBy="articulos")
     * @ORM\JoinColumn(name="oficina_id", referencedColumnName="id")
     */
    private $oficina;

    /**
     * @ORM\OneToOne(targetEntity="EstadoArticulo")
     * @ORM\JoinColumn(name="estado_articulo_id", referencedColumnName="id")
     */
    private $estadoArticulo;

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
     * Set $denominacion
     *
     * @param string denominacion
     *
     * @return Articulo
     */
    public function setDenominacion($denominacion)
    {
        $this->denominacion = $denominacion;

        return $this;
    }

    /**
     * Get denominacion
     *
     * @return string
     */
    public function getDenominacion()
    {
        return $this->denominacion;
    }

    /**
     * Set numeroInventario
     *
     * @param integer $numeroInventario
     *
     * @return Articulo
     */
    public function setNumeroInventario($numeroInventario)
    {
        $this->numeroInventario = $numeroInventario;

        return $this;
    }

    /**
     * Get numeroInventario
     *
     * @return int
     */
    public function getNumeroInventario()
    {
        return $this->numeroInventario;
    }

    /**
     * Set oficina
     *
     * @param Oficina $oficina
     *
     * @return Articulo
     */
    public function setOficina($oficina)
    {
        $this->oficina = $oficina;

        return $this;
    }

    /**
     * Get oficina
     *
     * @return Oficina
     */
    public function getOficina()
    {
        return $this->oficina;
    }

    /**
     * Set oficina
     *
     * @param EstadoArticulo $oficina
     *
     * @return Articulo
     */
    public function setEstadoArticulo($estadoArticulo)
    {
        $this->estadoArticulo = $estadoArticulo;

        return $this;
    }

    /**
     * Get oficina
     *
     * @return EstadoArticulo
     */
    public function getEstadoArticulo()
    {
        return $this->estadoArticulo;
    }
}
