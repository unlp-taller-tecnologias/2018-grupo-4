<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Moneda
 *
 * @ORM\Table(name="moneda")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MonedaRepository")
 */
class Moneda
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
     * @ORM\Column(name="moneda", type="string", length=50)
     */
    private $moneda;


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

    public function __toString() {
        return $this->moneda;
    }
}
