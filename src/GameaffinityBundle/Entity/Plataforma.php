<?php

namespace GameaffinityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \GameaffinityBundle\Util\Utils;
use \Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\JoinTable;
/**
 * Plataforma
 *
 * @ORM\Table(name="plataforma")
 * @ORM\Entity(repositoryClass="GameaffinityBundle\Repository\PlataformaRepository")
 */
class Plataforma
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_lanzamiento", type="datetime")
     */
    private $fechaLanzamiento;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     *
     * @ORM\ManyToMany(targetEntity="GameaffinityBundle\Entity\Juego", mappedBy="plataformas")
     * @JoinTable(name="mtm_plataformas_juegos")
     * 
     */
    private $juegos;
    
    public function __construct() {
        $this->juegos = new ArrayCollection();
    }
    
    
    public function getJuegos(){
        return $this->juegos;
    }
    
    public function addJuego(\GameaffinityBundle\Entity\Juego $juego){
        $this->juegos->add($juego);
    }
    
    public function removeJuego(\GameaffinityBundle\Entity\Juego $juego){
        $this->juegos->removeElement($juego);
    }
    
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Plataforma
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
     * Set fechaLanzamiento
     *
     * @param \DateTime $fechaLanzamiento
     *
     * @return Plataforma
     */
    public function setFechaLanzamiento($fechaLanzamiento)
    {
        $this->fechaLanzamiento = $fechaLanzamiento;

        return $this;
    }

    /**
     * Get fechaLanzamiento
     *
     * @return \DateTime
     */
    public function getFechaLanzamiento()
    {
        return $this->fechaLanzamiento;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Plataforma
     */
    public function setSlug($nombre)
    {
        $this->slug = Utils::getSlug("system-" . $nombre);

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
    
    public function __toString() {
        return $this->nombre;
    }
}

