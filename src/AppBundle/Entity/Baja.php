<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Baja
 *
 * @ORM\Table(name="baja")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BajaRepository")
 */
class Baja
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
     * @ORM\Column(name="expediente", type="integer")
     */
    private $expediente;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="string", length=255)
     */
    private $observaciones;

    /**
     * @var int
     *
     * @ORM\Column(name="finalizada", type="integer")
     */
    private $finalizada;



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
     * Set expediente
     *
     * @param integer $expediente
     *
     * @return Baja
     */
    public function setExpediente($expediente)
    {
        $this->expediente = $expediente;

        return $this;
    }

    /**
     * Get expediente
     *
     * @return int
     */
    public function getExpediente()
    {
        return $this->expediente;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return Baja
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
     * Set finalizada
     *
     * @param integer $finalizada
     *
     * @return Baja
     */
    public function setFinalizada($finalizada)
    {
        $this->finalizada = $finalizada;

        return $this;
    }

    /**
     * Get finalizada
     *
     * @return int
     */
    public function getFinalizada()
    {
        return $this->finalizada;
    }



    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Oficina")
     * @ORM\JoinColumn(name="oficina_id", referencedColumnName="id")
     */
    private $oficina;

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
     * @param string $oficina
     *
     * @return Oficina
     */
    public function setOficina($oficina)
    {
        $this->oficina = $oficina;

        return $this;
    }
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $usuario;

    /**
     * Get usuario
     *
     * @return User
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return User
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
        return $this;
    }


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;



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
     * @ORM\OneToMany(targetEntity="Historial", mappedBy="baja")
     */
    private $historiales;

    public function __construct($oficina=null)
    {
        $this->oficina = $oficina;
        $this->historiales = new ArrayCollection();
        $this->finalizada = 0;
        $this->fecha = new \DateTime();
    }


    public function __toString() {

    }

    /**
     * Get historiales
     *
     * @return int
     */
    public function getHistoriales()
    {
        return $this->historiales;
    }

    /**
     * Set historiales
     *
     * @param string $historiales
     *
     * @return Historial
     */
    public function setHistoriales($historiales)
    {
        $this->historiales = $historiales;

        return $this;
    }


}
