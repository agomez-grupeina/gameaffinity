<?php

namespace GameaffinityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\JoinTable;
use GameaffinityBundle\Util\Utils;

/**
 * Genero
 *
 * @ORM\Table(name="genero")
 * @ORM\Entity(repositoryClass="GameaffinityBundle\Repository\GeneroRepository")
 */
class Genero
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
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string
     * 
     * @ORM\ManyToMany(targetEntity="GameaffinityBundle\Entity\Juego", mappedBy="generos")
     * @JoinTable(name="mtm_juegos_generos")
     */
    private $juegos;
    
    public function __construct(){
        $this->juegos = new ArrayCollection();
    }
    
    public function getSlug(){
        return $this->slug;
    }
    
    public function setSlug(){
        $this->slug = Utils::getSlug($this->nombre);
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
     * @return Genero
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
     * Get juegos
     * 
     * @return ArrayCollection Juego
     */
    public function getJuegos(){
        return $this->juegos;
    }
    
    public function __toString() {
        return $this->nombre;
    }
    
}

