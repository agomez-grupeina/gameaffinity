<?php

namespace GameaffinityBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use GameaffinityBundle\Entity\Juego;
//Necesario para ordered fixture
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
//Necesario para pasar objetos como referencias en los fixtures
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadJuegoData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {

        $juegos = array(
            array('nombre' => 'Juego 1', 'fecha_lanzamiento' => new \DateTime('today - 2 days'), 'plataforma' => array($this->getReference('system-playstation-3')), 'genero' => array($this->getReference('accion'), $this->getReference('aventuras')), 'desarrolladora' => $this->getReference('dev-microsoft')),
            array('nombre' => 'Juego 2', 'fecha_lanzamiento' => new \DateTime('today - 2 days'), 'plataforma' => array($this->getReference('system-playstation-4')), 'genero' => array($this->getReference('estrategia'), $this->getReference('accion'), $this->getReference('rol')), 'desarrolladora' => $this->getReference('dev-konami')),
            array('nombre' => 'Juego 3', 'fecha_lanzamiento' => new \DateTime('today - 3 days'), 'plataforma' => array($this->getReference('system-xbox'), $this->getReference('system-pc')), 'genero' => array($this->getReference('disparos-primera-persona')), 'desarrolladora' => $this->getReference('dev-sony')),
            array('nombre' => 'Juego 4', 'fecha_lanzamiento' => new \DateTime('today - 3 days'), 'plataforma' => array($this->getReference('system-xbox360'), $this->getReference('system-playstation-3')), 'genero' => array($this->getReference('disparos-tercera-persona')), 'desarrolladora' => $this->getReference('dev-mierdasoft')),
            array('nombre' => 'Juego 5', 'fecha_lanzamiento' => new \DateTime('today - 4 days'), 'plataforma' => array($this->getReference('system-pc')), 'genero' => array($this->getReference('rol'), $this->getReference('accion')), 'desarrolladora' => $this->getReference('dev-square-enix')),
            array('nombre' => 'Juego 6', 'fecha_lanzamiento' => new \DateTime('today - 4 days'), 'plataforma' => array($this->getReference('system-playstation-3')), 'genero' => array($this->getReference('accion'), $this->getReference('disparos-primera-persona')), 'desarrolladora' => $this->getReference('dev-sony')),
            array('nombre' => 'Juego 7', 'fecha_lanzamiento' => new \DateTime('today - 5 days'), 'plataforma' => array($this->getReference('system-playstation-4')), 'genero' => array($this->getReference('aventuras'), $this->getReference('disparos-tercera-persona')), 'desarrolladora' => $this->getReference('dev-microsoft')),
            array('nombre' => 'Juego 8', 'fecha_lanzamiento' => new \DateTime('today - 10 days'), 'plataforma' => array($this->getReference('system-pc')), 'genero' => array($this->getReference('accion')), 'desarrolladora' => $this->getReference('dev-konami')),
        );

        foreach ($juegos as $juego) {                       
            $entity = new Juego();
            $entity->setNombre($juego['nombre']);
            
            
            $entity->setSlug();
            $entity->setFechaLanzamiento($juego['fecha_lanzamiento']);            
            
            foreach ($juego['genero'] as $genero){
                $entity->addGenero($genero);
            }
            
            foreach ($juego['plataforma'] as $plataforma){
                $entity->addPlataforma($plataforma);
            }
            
            
//            $entity->addPlataforma($juego['plataforma']);
//            $entity->addGenero($juego['genero']);
            
            $entity->setDesarrolladora($juego['desarrolladora']);
            
            $this->addReference($entity->getSlug(), $entity);
            
            $manager->persist($entity);
        }

        $manager->flush();
    }

    public function getOrder() {
        return 2;
    }

}
