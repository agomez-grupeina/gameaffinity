<?php

namespace GameaffinityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

use \GameaffinityBundle\Util\Utils;
/**
 * Desarrolladora
 *
 * @ORM\Table(name="desarrolladora")
 * @ORM\Entity(repositoryClass="GameaffinityBundle\Repository\DesarrolladoraRepository")
 */
class Desarrolladora {

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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fundacion", type="datetime")
     */
    private $fechaFundacion;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="GameaffinityBundle\Entity\Juego", mappedBy="desarrolladora")
     */
    private $juegos;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    public function __construct(){
        $this->juegos = new ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Desarrolladora
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Set fechaFundacion
     *
     * @param \DateTime $fechaFundacion
     *
     * @return Desarrolladora
     */
    public function setFechaFundacion($fechaFundacion) {
        $this->fechaFundacion = $fechaFundacion;

        return $this;
    }

    /**
     * Get fechaFundacion
     *
     * @return \DateTime
     */
    public function getFechaFundacion() {
        return $this->fechaFundacion;
    }

    /**
     * Set juegos
     *
     * @param string $juegos
     *
     * @return Desarrolladora
     */
    public function setJuegos(ArrayCollection $juegos) {
        $this->juegos = $juegos;

        return $this;
    }

    /**
     * Get juegos
     *
     * @return string
     */
    public function getJuegos() {
        return $this->juegos;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Desarrolladora
     */
    public function setSlug($nombre) {
        $this->slug = Utils::getSlug("dev-" . $nombre);

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }
    
    public function addJuego(\GameaffinityBundle\Entity\Juego $juego){
        $this->juegos->add($juego);
    }
    
    public function removeJuego($juego){
        $this->juegos->removeElement($juego);
    }
    
    public function __toString(){
        return $this->nombre;
    }

}
