<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tipo
 *
 * @ORM\Table(name="tipo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TipoRepository")
 */
class Tipo
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
     * @ORM\Column(name="codigo", type="integer", unique=true)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="nomenclador", type="string", length=100)
     */
    private $nomenclador;

    /**
     * @var string
     *
     * @ORM\Column(name="cuenta", type="string", length=100)
     */
    private $cuenta;

    /**
     * @var string
     *
     * @ORM\Column(name="concepto", type="string", length=100)
     */
    private $concepto;

    /**
     * @var string
     *
     * @ORM\Column(name="grupo", type="string", length=100, nullable=true)
     */
    private $grupo;

    /**
     * @var string
     *
     * @ORM\Column(name="subgrupo", type="string", length=100, nullable=true)
     */
    private $subgrupo;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @var int
     *
     * @ORM\Column(name="vida_util", type="integer", nullable=true)
     */
    private $vidaUtil;


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
     * Set codigo
     *
     * @param integer $codigo
     *
     * @return Tipo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set nomenclador
     *
     * @param string $nomenclador
     *
     * @return Tipo
     */
    public function setNomenclador($nomenclador)
    {
        $this->nomenclador = $nomenclador;

        return $this;
    }

    /**
     * Get nomenclador
     *
     * @return string
     */
    public function getNomenclador()
    {
        return $this->nomenclador;
    }

    /**
     * Set cuenta
     *
     * @param string $cuenta
     *
     * @return Tipo
     */
    public function setCuenta($cuenta)
    {
        $this->cuenta = $cuenta;

        return $this;
    }

    /**
     * Get cuenta
     *
     * @return string
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }

    /**
     * Set concepto
     *
     * @param string $concepto
     *
     * @return Tipo
     */
    public function setConcepto($concepto)
    {
        $this->concepto = $concepto;

        return $this;
    }

    /**
     * Get concepto
     *
     * @return string
     */
    public function getConcepto()
    {
        return $this->concepto;
    }

    /**
     * Set grupo
     *
     * @param string $grupo
     *
     * @return Tipo
     */
    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo
     *
     * @return string
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * Set subgrupo
     *
     * @param string $subgrupo
     *
     * @return Tipo
     */
    public function setSubgrupo($subgrupo)
    {
        $this->subgrupo = $subgrupo;

        return $this;
    }

    /**
     * Get subgrupo
     *
     * @return string
     */
    public function getSubgrupo()
    {
        return $this->subgrupo;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Tipo
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set vidaUtil
     *
     * @param integer $vidaUtil
     *
     * @return Tipo
     */
    public function setVidaUtil($vidaUtil)
    {
        $this->vidaUtil = $vidaUtil;

        return $this;
    }

    /**
     * Get vidaUtil
     *
     * @return int
     */
    public function getVidaUtil()
    {
        return $this->vidaUtil;
    }

    public function __toString() {
        return $this->nomenclador;
    }
}
