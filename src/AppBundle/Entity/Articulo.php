<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Articulo
 *
 * @ORM\Table(name="articulo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticuloRepository")
 * @UniqueEntity(
 *   fields={"numInventario"},
 *   message="El número de inventario ya existe."
 * )
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

    private $condiciones;

    /**
     * @var string
     *
     * @ORM\Column(name="num_inventario", type="string", length=100, unique=true)
     */
    private $numInventario;

    /**
     * @var string
     *
     * @ORM\Column(name="denominacion", type="string", length=100)
     */
    private $denominacion;

    /**
     * @var string
     *
     * @ORM\Column(name="moneda", type="string", length=100)
     */
    private $moneda;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Estado")
     * @ORM\JoinColumn(name="estado_id", referencedColumnName="id")
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="estadoAdicional")
     * @ORM\JoinColumn(name="estadoAdicional_id", referencedColumnName="id")
     */
    private $estadoAdicional;


    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Condicion")
     * @ORM\JoinColumn(name="condicion_id", referencedColumnName="id")
     */
    private $condicion;

    /**
     * @var Tipo
     *
     * @ORM\ManyToOne(targetEntity="Tipo")
     * @ORM\JoinColumn(name="tipo_id", referencedColumnName="id")
     */
    private $tipo;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="material", type="string", length=100, nullable=true)
     */
    private $material;

    /**
     * @var string
     *
     * @ORM\Column(name="marca", type="string", length=100, nullable=true)
     */
    private $marca;

    /**
     * @var int
     *
     * @ORM\Column(name="num_fabrica", type="integer", nullable=true)
     */
    private $numFabrica;

    /**
     * @var string
     *
     * @ORM\Column(name="largo", type="string", length=100, nullable=true)
     */
    private $largo;

    /**
     * @var string
     *
     * @ORM\Column(name="ancho", type="string", length=100, nullable=true)
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
     * @ORM\Column(name="nums_estantes", type="integer", nullable=true)
     */
    private $numsEstantes;

    /**
     * @var int
     *
     * @ORM\Column(name="nums_cajones", type="integer", nullable=true)
     */
    private $numsCajones;

    /**
     * @var string
     *
     * @ORM\Column(name="detalle_origen", type="string", length=150, nullable=true)
     */
    private $detalleOrigen;

    /**
     * @var float
     *
     * @ORM\Column(name="importe", type="float", nullable=true)
     */
    private $importe;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_entrada", type="datetime", nullable=true)
     */
    private $fechaEntrada;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_cuenta_subcuenta", type="string", length=100, nullable=true)
     */
    private $codigoCuentaSubcuenta;

    /**
     * @var int
     *
     * @ORM\Column(name="num_expediente", type="integer", nullable=true)
     */
    private $numExpediente;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="string", length=255, nullable=true)
     */
    private $observaciones;

    /**
    * @ORM\ManyToOne(targetEntity="Oficina", inversedBy="articulos")
    * @ORM\JoinColumn(name="oficina_id", referencedColumnName="id", nullable=false)
    */
    private $oficina;

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
     * @param string $numInventario
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
     * @return string
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
     * Set estado
     *
     * @param string $estado
     *
     * @return Articulo
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }


    /**
     * Set estadoAdicional
     *
     * @param string $estadoAdicional
     *
     * @return Articulo
     */
    public function setEstadoAdicional($estadoAdicional)
    {
        $this->estadoAdicional = $estadoAdicional;

        return $this;
    }

    /**
     * Get estadoAdicional
     *
     * @return string
     */
    public function getEstadoAdicional()
    {
        return $this->estadoAdicional;
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
     * @param string $largo
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
     * @return string
     */
    public function getLargo()
    {
        return $this->largo;
    }

    /**
     * Set ancho
     *
     * @param string $ancho
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
     * @return string
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
     * Set numsEstantes
     *
     * @param integer $numsEstantes
     *
     * @return Articulo
     */
    public function setNumsEstantes($numsEstantes)
    {
        $this->numsEstantes = $numsEstantes;

        return $this;
    }

    /**
     * Get numsEstantes
     *
     * @return int
     */
    public function getNumsEstantes()
    {
        return $this->numsEstantes;
    }

    /**
     * Set numsCajones
     *
     * @param integer $numsCajones
     *
     * @return Articulo
     */
    public function setNumsCajones($numsCajones)
    {
        $this->numsCajones = $numsCajones;

        return $this;
    }

    /**
     * Get numsCajones
     *
     * @return int
     */
    public function getNumsCajones()
    {
        return $this->numsCajones;
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
     * Set fechaEntrada
     *
     * @param \DateTime $fechaEntrada
     *
     * @return Articulo
     */
    public function setFechaEntrada($fechaEntrada)
    {
        $this->fechaEntrada = $fechaEntrada;

        return $this;
    }

    /**
     * Get fechaEntrada
     *
     * @return \DateTime
     */
    public function getFechaEntrada()
    {
        return $this->fechaEntrada;
    }

    /**
     * Set codigoCuentaSubcuenta
     *
     * @param string $codigoCuentaSubcuenta
     *
     * @return Articulo
     */
    public function setCodigoCuentaSubcuenta($codigoCuentaSubcuenta)
    {
        $this->codigoCuentaSubcuenta = $codigoCuentaSubcuenta;

        return $this;
    }

    /**
     * Get codigoCuentaSubcuenta
     *
     * @return string
     */
    public function getCodigoCuentaSubcuenta()
    {
        return $this->codigoCuentaSubcuenta;
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
     * Set oficina
     *
     * @param string $oficina
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
     * Get tipo
     *
     * @return Tipo
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return Tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Set moneda.
     *
     * @param string $moneda
     *
     * @return Moneda
     */
    public function setMoneda($moneda)
    {
        $this->moneda = $moneda;

        return $this;
    }

    /**
     * Get moneda.
     *
     * @return string
     */
    public function getMoneda()
    {
        return $this->moneda;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Articulo
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
     * Set habilitado
     *
     * @param boolean $habilitado
     *
     * @return Articulo
     */
    public function setHabilitado($habilitado)
    {
        $this->habilitado = $habilitado;

        return $this;
    }

    /**
     * Get habilitado
     *
     * @return bool
     */
    public function getHabilitado()
    {
        return $this->habilitado;
    }

    /**
     * Set user.
     *
     * @param string $user
     *
     * @return User
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $user;
    }

    /**
     * Get user.
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;

    }

    /**
     * Get condicion
     *
     * @return Condicion
     */
    public function getCondicion()
    {
        return $this->condicion;
    }

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
     * @ORM\OneToMany(targetEntity="Historial", mappedBy="articulo")
     */
    private $historiales;

    public function getHistoriales() {
        return $this->historiales;
    }

    public function addHistorial($historiales) {
        $this->historiales[] = $historiales;
    }


    public function __construct($id, $oficina) {
        $this->fechaEntrada = new \DateTime();
        $this->numInventario = $id;
        $this->oficina = $oficina;
        $this->historiales = new ArrayCollection();
    }

    public function getNumInventarioINT()
    {
      $porciones = explode("-", $this->numInventario);
      return $porciones[2];
    }
}
