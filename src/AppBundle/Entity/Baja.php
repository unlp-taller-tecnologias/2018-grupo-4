<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var string
     *
     * @ORM\Column(name="responsable_baja", type="string", length=100)
     */
    private $responsableBaja;


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
     * Set responsableBaja
     *
     * @param string $responsableBaja
     *
     * @return Baja
     */
    public function setResponsableBaja($responsableBaja)
    {
        $this->responsableBaja = $responsableBaja;

        return $this;
    }

    /**
     * Get responsableBaja
     *
     * @return string
     */
    public function getResponsableBaja()
    {
        return $this->responsableBaja;
    }
}

