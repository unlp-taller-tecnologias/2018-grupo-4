<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Articulo
 *
 * @ORM\Table(name="articulo")
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
     * @var int
     *
     * @ORM\Column(name="num_inventario", type="integer", unique=true)
     */
    private $numInventario;

    /**
     * @var string
     *
     * @ORM\Column(name="denominacion", type="string", length=255)
     */
    private $denominacion;

    /**
     * @var string
     *
     * @ORM\Column(name="id_usuario", type="string", length=255)
     */
    private $idUsuario;

    /**
     * @var int
     *
     * @ORM\Column(name="id_u", type="integer")
     */
    private $idU;

    /**
     * @var int
     *
     * @ORM\Column(name="id_estado", type="integer")
     */
    private $idEstado;

    /**
     * @var string
     *
     * @ORM\Column(name="material", type="string", length=255, nullable=true)
     */
    private $material;

    /**
     * @var string
     *
     * @ORM\Column(name="marca", type="string", length=255, nullable=true)
     */
    private $marca;

    /**
     * @var int
     *
     * @ORM\Column(name="num_fabrica", type="integer", nullable=true)
     */
    private $numFabrica;

    /**
     * @var float
     *
     * @ORM\Column(name="largo", type="float", nullable=true)
     */
    private $largo;

    /**
     * @var float
     *
     * @ORM\Column(name="ancho", type="float", nullable=true)
     */
    private $ancho;

    /**
     * @var float
     *
     * @ORM\Column(name="alto", type="float", nullable=true)
     */
    private $alto;

    /**
     * @var int
     *
     * @ORM\Column(name="num_estantes", type="integer", nullable=true)
     */
    private $numEstantes;

    /**
     * @var int
     *
     * @ORM\Column(name="num_cajones", type="integer", nullable=true)
     */
    private $numCajones;

    /**
     * @var string
     *
     * @ORM\Column(name="detalle_origen", type="string", length=255, nullable=true)
     */
    private $detalleOrigen;

    /**
     * @var float
     *
     * @ORM\Column(name="importe", type="float", nullable=true)
     */
    private $importe;

    /**
     * @var int
     *
     * @ORM\Column(name="cod_cuenta", type="integer", nullable=true)
     */
    private $codCuenta;

    /**
     * @var int
     *
     * @ORM\Column(name="num_expediente", type="integer", nullable=true, unique=true)
     */
    private $numExpediente;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="string", length=255, nullable=true)
     */
    private $observaciones;

    /**
     * @var int
     *
     * @ORM\Column(name="id_condicion", type="integer", nullable=true)
     */
    private $idCondicion;

    /**
     * @var int
     *
     * @ORM\Column(name="id_tipo", type="integer")
     */
    private $idTipo;


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
     * Set numInventario
     *
     * @param integer $numInventario
     *
     * @return Articulo
     */
    public function setNumInventario($numInventario)
    {
        $this->numInventario = $numInventario;

        return $this;
    }

    /**
     * Get numInventario
     *
     * @return int
     */
    public function getNumInventario()
    {
        return $this->numInventario;
    }

    /**
     * Set denominacion
     *
     * @param string $denominacion
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
     * Set idUsuario
     *
     * @param string $idUsuario
     *
     * @return Articulo
     */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get idUsuario
     *
     * @return string
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set idU
     *
     * @param integer $idU
     *
     * @return Articulo
     */
    public function setIdU($idU)
    {
        $this->idU = $idU;

        return $this;
    }

    /**
     * Get idU
     *
     * @return int
     */
    public function getIdU()
    {
        return $this->idU;
    }

    /**
     * Set idEstado
     *
     * @param integer $idEstado
     *
     * @return Articulo
     */
    public function setIdEstado($idEstado)
    {
        $this->idEstado = $idEstado;

        return $this;
    }

    /**
     * Get idEstado
     *
     * @return int
     */
    public function getIdEstado()
    {
        return $this->idEstado;
    }

    /**
     * Set material
     *
     * @param string $material
     *
     * @return Articulo
     */
    public function setMaterial($material)
    {
        $this->material = $material;

        return $this;
    }

    /**
     * Get material
     *
     * @return string
     */
    public function getMaterial()
    {
        return $this->material;
    }

    /**
     * Set marca
     *
     * @param string $marca
     *
     * @return Articulo
     */
    public function setMarca($marca)
    {
        $this->marca = $marca;

        return $this;
    }

    /**
     * Get marca
     *
     * @return string
     */
    public function getMarca()
    {
        return $this->marca;
    }

    /**
     * Set numFabrica
     *
     * @param integer $numFabrica
     *
     * @return Articulo
     */
    public function setNumFabrica($numFabrica)
    {
        $this->numFabrica = $numFabrica;

        return $this;
    }

    /**
     * Get numFabrica
     *
     * @return int
     */
    public function getNumFabrica()
    {
        return $this->numFabrica;
    }

    /**
     * Set largo
     *
     * @param float $largo
     *
     * @return Articulo
     */
    public function setLargo($largo)
    {
        $this->largo = $largo;

        return $this;
    }

    /**
     * Get largo
     *
     * @return float
     */
    public function getLargo()
    {
        return $this->largo;
    }

    /**
     * Set ancho
     *
     * @param float $ancho
     *
     * @return Articulo
     */
    public function setAncho($ancho)
    {
        $this->ancho = $ancho;

        return $this;
    }

    /**
     * Get ancho
     *
     * @return float
     */
    public function getAncho()
    {
        return $this->ancho;
    }

    /**
     * Set alto
     *
     * @param float $alto
     *
     * @return Articulo
     */
    public function setAlto($alto)
    {
        $this->alto = $alto;

        return $this;
    }

    /**
     * Get alto
     *
     * @return float
     */
    public function getAlto()
    {
        return $this->alto;
    }

    /**
     * Set numEstantes
     *
     * @param integer $numEstantes
     *
     * @return Articulo
     */
    public function setNumEstantes($numEstantes)
    {
        $this->numEstantes = $numEstantes;

        return $this;
    }

    /**
     * Get numEstantes
     *
     * @return int
     */
    public function getNumEstantes()
    {
        return $this->numEstantes;
    }

    /**
     * Set numCajones
     *
     * @param integer $numCajones
     *
     * @return Articulo
     */
    public function setNumCajones($numCajones)
    {
        $this->numCajones = $numCajones;

        return $this;
    }

    /**
     * Get numCajones
     *
     * @return int
     */
    public function getNumCajones()
    {
        return $this->numCajones;
    }

    /**
     * Set detalleOrigen
     *
     * @param string $detalleOrigen
     *
     * @return Articulo
     */
    public function setDetalleOrigen($detalleOrigen)
    {
        $this->detalleOrigen = $detalleOrigen;

        return $this;
    }

    /**
     * Get detalleOrigen
     *
     * @return string
     */
    public function getDetalleOrigen()
    {
        return $this->detalleOrigen;
    }

    /**
     * Set importe
     *
     * @param float $importe
     *
     * @return Articulo
     */
    public function setImporte($importe)
    {
        $this->importe = $importe;

        return $this;
    }

    /**
     * Get importe
     *
     * @return float
     */
    public function getImporte()
    {
        return $this->importe;
    }

    /**
     * Set codCuenta
     *
     * @param integer $codCuenta
     *
     * @return Articulo
     */
    public function setCodCuenta($codCuenta)
    {
        $this->codCuenta = $codCuenta;

        return $this;
    }

    /**
     * Get codCuenta
     *
     * @return int
     */
    public function getCodCuenta()
    {
        return $this->codCuenta;
    }

    /**
     * Set numExpediente
     *
     * @param integer $numExpediente
     *
     * @return Articulo
     */
    public function setNumExpediente($numExpediente)
    {
        $this->numExpediente = $numExpediente;

        return $this;
    }

    /**
     * Get numExpediente
     *
     * @return int
     */
    public function getNumExpediente()
    {
        return $this->numExpediente;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return Articulo
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set idCondicion
     *
     * @param integer $idCondicion
     *
     * @return Articulo
     */
    public function setIdCondicion($idCondicion)
    {
        $this->idCondicion = $idCondicion;

        return $this;
    }

    /**
     * Get idCondicion
     *
     * @return int
     */
    public function getIdCondicion()
    {
        return $this->idCondicion;
    }

    /**
     * Set idTipo
     *
     * @param integer $idTipo
     *
     * @return Articulo
     */
    public function setIdTipo($idTipo)
    {
        $this->idTipo = $idTipo;

        return $this;
    }

    /**
     * Get idTipo
     *
     * @return int
     */
    public function getIdTipo()
    {
        return $this->idTipo;
    }
}
