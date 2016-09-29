<?php

namespace GameaffinityBundle\Entity;

use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use \Doctrine\Common\Collections\ArrayCollection;
use \GameaffinityBundle\Util\Utils;
use Symfony\Component\Validator\Constraints as Assert;
use GameaffinityBundle\Validator\Constraints as AcmeAssert;

/**
 * Juego
 *
 * @ORM\Table(name="juego")
 * @ORM\Entity(repositoryClass="GameaffinityBundle\Repository\JuegoRepository")
 */
class Juego {

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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_lanzamiento", type="datetime")
     */
    private $fechaLanzamiento;

    /**
     * @var string
     * 
     * @AcmeAssert\PlataformasNotNull
     * 
     * @ORM\ManyToMany(targetEntity="GameaffinityBundle\Entity\Plataforma", inversedBy="juegos")
     * @JoinTable(name="mtm_plataformas_juegos")
     * 
     */
    private $plataformas;

    /**
     * @var string
     * 
     * @ORM\ManyToMany(targetEntity="GameaffinityBundle\Entity\Genero", inversedBy="juegos")
     * @JoinTable(name="mtm_juegos_generos")
     */
    private $generos;

    /**
     * @var string
     *
     * @ORM\Column(name="puntuacion", type="decimal", precision=4, scale=2, nullable=true)
     */
    private $puntuacion;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="GameaffinityBundle\Entity\Desarrolladora", inversedBy="juegos")
     */
    private $desarrolladora;

    /**
     *
     * @var string 
     * 
     * @ORM\Column(name="portada", type="string", length=255, nullable=true)
     */
    private $portada;

    //private $fotos;

    public function __construct() {
        $this->generos = new ArrayCollection();
        $this->plataformas = new ArrayCollection();
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
     * Set titulo
     *
     * @param string $titulo
     *
     * @return Juego
     */
    public function setTitulo($titulo) {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo() {
        return $this->titulo;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Juego
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Juego
     */
    public function setSlug() {
        $this->slug = Utils::getSlug($this->nombre);

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

    /**
     * Set fechaLanzamiento
     *
     * @param \DateTime $fechaLanzamiento
     *
     * @return Juego
     */
    public function setFechaLanzamiento($fechaLanzamiento) {
        $this->fechaLanzamiento = $fechaLanzamiento;

        return $this;
    }

    /**
     * Get fechaLanzamiento
     *
     * @return \DateTime
     */
    public function getFechaLanzamiento() {
        return $this->fechaLanzamiento;
    }

    /**
     * Set plataforma
     *
     * @param ArrayCollection $plataformas
     *
     * @return Juego
     */
    public function setPlataformas(ArrayCollection $plataformas) {
        $this->plataformas = $plataformas;

        return $this;
    }

    /**
     * Get plataforma
     *
     * @return string
     */
    public function getPlataformas() {
        return $this->plataformas;
    }

    /**
     * Set genero
     *
     * @param ArrayCollection $generos
     *
     * @return Juego
     */
    public function setGeneros(ArrayCollection $generos) {
        $this->generos = $generos;

        return $this;
    }

    /**
     * Get genero
     *
     * @return string
     */
    public function getGeneros() {
        return $this->generos;
    }

    public function addGenero(\GameaffinityBundle\Entity\Genero $genero) {
        $this->generos->add($genero);
    }

    public function removeGenero(\GameaffinityBundle\Entity\Genero $genero) {
        $this->generos->removeElement($genero);
    }

    public function addPlataforma(\GameaffinityBundle\Entity\Plataforma $plataforma) {
        $this->plataformas->add($plataforma);
    }

    public function removePlataforma(\GameaffinityBundle\Entity\Plataforma $plataforma) {
        $this->plataformas->removeElement($plataforma);
    }

    /**
     * Set puntuacion
     *
     * @param string $puntuacion
     *
     * @return Juego
     */
    public function setPuntuacion($puntuacion) {
        $this->puntuacion = $puntuacion;

        return $this;
    }

    /**
     * Get puntuacion
     *
     * @return string
     */
    public function getPuntuacion() {
        return $this->puntuacion;
    }

    /**
     * Set desarrolladora
     *
     * @param string $desarrolladora
     *
     * @return Juego
     */
    public function setDesarrolladora(\GameaffinityBundle\Entity\Desarrolladora $desarrolladora) {
        $this->desarrolladora = $desarrolladora;

        return $this;
    }

    /**
     * Get desarrolladora
     *
     * @return string
     */
    public function getDesarrolladora() {
        return $this->desarrolladora;
    }

    public function getPortada() {
        return $this->portada;
    }

    public function setPortada($portada) {
        $this->portada = $portada;

        return $this;
    }
//    
//    //Funcion utilizada al editar Juego 
//    public function vaciarGeneros(){
//        $this->generos = new ArrayCollection();
//    }
//    //Funcion utilizada al editar Juego 
//    public function vaciarPlataformas(){
//        $this->plataformas = new ArrayCollection();
//    }

}
