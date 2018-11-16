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
<<<<<<< HEAD
     * @var string
     *
     * @ORM\Column(name="moneda", type="string", length=50)
=======
     * @var string|null
     *
     * @ORM\Column(name="moneda", type="string", length=50, nullable=true)
>>>>>>> 40b9756b45a6c890848d1c0ca4ab7634bd3a9c72
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
<<<<<<< HEAD
     * @param string $moneda
     *
     * @return Moneda
     */
    public function setMoneda($moneda)
=======
     * @param string|null $moneda
     *
     * @return Moneda
     */
    public function setMoneda($moneda = null)
>>>>>>> 40b9756b45a6c890848d1c0ca4ab7634bd3a9c72
    {
        $this->moneda = $moneda;

        return $this;
    }

    /**
     * Get moneda.
     *
<<<<<<< HEAD
     * @return string
=======
     * @return string|null
>>>>>>> 40b9756b45a6c890848d1c0ca4ab7634bd3a9c72
     */
    public function getMoneda()
    {
        return $this->moneda;
    }

    public function __toString() {
        return $this->moneda;
    }
}
