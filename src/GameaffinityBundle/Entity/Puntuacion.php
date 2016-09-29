<?php

/*Puntuacion: nota dada por x usuario a un juego concreto*/

namespace GameaffinityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Puntuacion
 *
 * @ORM\Table(name="puntuacion")
 * @ORM\Entity(repositoryClass="GameaffinityBundle\Repository\PuntuacionRepository")
 */
class Puntuacion
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
     * @ORM\ManyToOne(targetEntity="GameaffinityBundle\Entity\Usuario")
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="GameaffinityBundle\Entity\Juego")
     */
    private $juego;

    /**
     * @var string
     *
     * @ORM\Column(name="nota", type="decimal", scale=4, precision=2)
     */
    private $nota;


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
     * Set usuario
     *
     * @param string $usuario
     *
     * @return Puntuacion
     */
    public function setUsuario(\GameaffinityBundle\Entity\Usuario $usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set juego
     *
     * @param string $juego
     *
     * @return Puntuacion
     */
    public function setJuego(\GameaffinityBundle\Entity\Juego $juego)
    {
        $this->juego = $juego;

        return $this;
    }

    /**
     * Get juego
     *
     * @return string
     */
    public function getJuego()
    {
        return $this->juego;
    }

    /**
     * Set puntuacion
     *
     * @param string $puntuacion
     *
     * @return Puntuacion
     */
    public function setNota($nota)
    {
        $this->nota = $nota;

        return $this;
    }

    /**
     * Get puntuacion
     *
     * @return string
     */
    public function getNota()
    {
        return $this->nota;
    }
}

