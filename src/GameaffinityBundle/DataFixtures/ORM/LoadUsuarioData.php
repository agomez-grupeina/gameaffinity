<?php

namespace GameaffinityBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use GameaffinityBundle\Entity\Usuario;

//Necesario para ordered fixture
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

//Necesario para pasar objetos como referencias en los fixtures
use Doctrine\Common\DataFixtures\AbstractFixture;

//Necesario para poder acceder al container
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUsuarioData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    //Container necesario para encriptacion de password
    private $container;
    
    public function setContainer(ContainerInterface $container = null){
        $this->container = $container;
    }
    
    public function load(ObjectManager $manager) {

        $usuarios = array(
            array('nombre' => 'Juan Antonio', 'email' => 'admin@mail.com', 'password' => '1234', 'role' => $this->getReference('ROLE_ADMIN')),
            array('nombre' => 'Alejandro', 'email' => 'manager@mail.com', 'password' => '1234', 'role' => $this->getReference('ROLE_MANAGER')),
            array('nombre' => 'Miguel', 'email' => 'user@mail.com', 'password' => '1234', 'role' => $this->getReference('ROLE_USER')),
        );

        foreach ($usuarios as $usuario) {
            $entity = new Usuario();
            $entity->setNombre($usuario['nombre']);
            $entity->setEmail($usuario['email']);
            $entity->setRole($usuario['role']);

            //Password encoding
            $plainPassword = $usuario['password'];
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($entity, $plainPassword);
            //END Password Encoding

            $entity->setPassword($encoded);

            $manager->persist($entity);
            
            $this->addReference($usuario['email'], $entity);
        }

        $manager->flush();
    }

    public function getOrder() {
        return 2;
    }

}
