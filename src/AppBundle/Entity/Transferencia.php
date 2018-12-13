<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Transferencia
 *
 * @ORM\Table(name="transferencia")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TransferenciaRepository")
 */
class Transferencia
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
       * @ORM\Column(name="observaciones", type="string", length=255, nullable=true )
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
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return Transferencia
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

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
     * @return Transferencia
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
     * @ORM\OneToMany(targetEntity="Historial", mappedBy="transferencia")
     */
    private $historiales;

    public function __construct($oficina)
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

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Oficina")
     * @ORM\JoinColumn(name="oficina_origen_id", referencedColumnName="id")
     */
    private $oficinaOrigen;

    /**
     * Get oficinaOrigen
     *
     * @return Oficina
     */
    public function getOficinaOrigen()
    {
        return $this->oficinaOrigen;
    }

    /**
     * Set oficinaOrigen
     *
     * @param string $oficinaOrigen
     *
     * @return Oficina
     */
    public function setOficinaOrigen($oficinaOrigen)
    {
        $this->oficinaOrigen = $oficinaOrigen;

        return $this;
    }



    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Oficina")
     * @ORM\JoinColumn(name="oficina_destino_id", referencedColumnName="id")
     */
    private $oficina_destino;

    /**
     * Get oficina_destino
     *
     * @return Oficina
     */
    public function getOficinaDestino()
    {
        return $this->oficina_destino;
    }

    /**
     * Set oficina_destino
     *
     * @param string $oficina_destino
     *
     * @return Oficina
     */
    public function setOficinaDestino($oficina_destino)
    {
        $this->oficina_destino = $oficina_destino;

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


}
