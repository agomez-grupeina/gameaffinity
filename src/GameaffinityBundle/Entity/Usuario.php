<?php

namespace GameaffinityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;
use \GameaffinityBundle\Util\Utils;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Usuario
 *
 * @ORM\Table(name="usuario")
 * @ORM\Entity(repositoryClass="GameaffinityBundle\Repository\UsuarioRepository")
 */
class Usuario implements UserInterface, \Serializable {

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
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_registro", type="datetime")
     */
    private $fechaRegistro;

    /**
     * @var string
     *
     * @ORM\ManyToMany(targetEntity="GameaffinityBundle\Entity\Juego")
     * @JoinTable(name="mtm_juegos_favoritos",
     *      joinColumns={@JoinColumn(name="id_usuario", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="id_juego", referencedColumnName="id")}
     *      )
     */
    private $juegosFavoritos;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="GameaffinityBundle\Entity\Role")
     */
    private $role;

    public function __construct() {
        $this->setFechaRegistro();
        $this->setSalt();
        $this->setJuegosFavoritos(new ArrayCollection());
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
     * @return Usuario
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
     * Set email
     *
     * @param string $email
     *
     * @return Usuario
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Usuario
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return Usuario
     */
    private function setSalt() {
        $this->salt = Utils::randomString();

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt() {
        return $this->salt;
    }

    /**
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     *
     * @return Usuario
     */
    private function setFechaRegistro() {
        $this->fechaRegistro = new \DateTime('now');

        return $this;
    }

    /**
     * Get fechaRegistro
     *
     * @return \DateTime
     */
    public function getFechaRegistro() {
        return $this->fechaRegistro;
    }

    /**
     * Set juegosFavoritos
     *
     * @param string $juegosFavoritos
     *
     * @return Usuario
     */
    public function setJuegosFavoritos(ArrayCollection $juegosFavoritos) {
        $this->juegosFavoritos = $juegosFavoritos;

        return $this;
    }

    /**
     * Get juegosFavoritos
     *
     * @return string
     */
    public function getJuegosFavoritos() {
        return $this->juegosFavoritos;
    }

    public function addJuegoFavorito(\GameaffinityBundle\Entity\Juego $juego) {
        $this->juegosFavoritos->add($juego);
    }

    public function removeJuegoFavorito(\GameaffinityBundle\Entity\Juego $juego) {
        $this->juegosFavoritos->removeElement($juego);
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return Usuario
     */
    public function setRole(\GameaffinityBundle\Entity\Role $role) {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole() {
        return $this->role;
    }

    public function serialize() {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
                // solo necesario si el algoritmo no es bcrypt
                 $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized) {
        list (
                $this->id,
                $this->email,
                $this->password,
                // solo necesario si el algoritmo no es bcrypt
                 $this->salt
                ) = unserialize($serialized);
    }
    
    
    public function getRoles(){
        return array($this->role->getNombre());
    }
    
    public function eraseCredentials(){
        
    }
    
    public function getUsername(){
        return $this->email;
    }

}
