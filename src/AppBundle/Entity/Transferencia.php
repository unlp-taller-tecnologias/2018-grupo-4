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

    public function __construct()
    {
        $this->historiales = new ArrayCollection();
    }

    public function __toString() {
        return $this->nombre;
    }




    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Oficina")
     * @ORM\JoinColumn(name="oficina_origen_id", referencedColumnName="id")
     */
    private $oficina_origen;

    /**
     * Get oficina_origen
     *
     * @return Oficina
     */
    public function getOficinaOrigen()
    {
        return $this->oficina_origen;
    }

    /**
     * Set oficina_origen
     *
     * @param string $oficina_origen
     *
     * @return Oficina
     */
    public function setOficinaOrigen($oficina_origen)
    {
        $this->oficina_origen = $oficina_origen;

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
