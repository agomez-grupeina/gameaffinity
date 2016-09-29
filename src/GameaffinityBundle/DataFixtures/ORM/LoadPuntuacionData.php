<?php

namespace GameaffinityBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use GameaffinityBundle\Entity\Puntuacion;
//Necesario para ordered fixture
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
//Necesario para pasar objetos como referencias en los fixtures
use Doctrine\Common\DataFixtures\AbstractFixture;

//use GameaffinityBundle\Util\Utils;

class LoadPuntuacionData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $puntuaciones = array(
            array('nota' => 7, 'usuario' => $this->getReference('admin@mail.com'), 'juego' => $this->getReference('juego-1')),
            array('nota' => 8, 'usuario' => $this->getReference('admin@mail.com'), 'juego' => $this->getReference('juego-2')),
            array('nota' => 6, 'usuario' => $this->getReference('admin@mail.com'), 'juego' => $this->getReference('juego-3')),
            array('nota' => 5, 'usuario' => $this->getReference('manager@mail.com'), 'juego' => $this->getReference('juego-1')),
            array('nota' => 4, 'usuario' => $this->getReference('manager@mail.com'), 'juego' => $this->getReference('juego-2')),
            array('nota' => 1, 'usuario' => $this->getReference('user@mail.com'), 'juego' => $this->getReference('juego-1')),
            array('nota' => 5, 'usuario' => $this->getReference('user@mail.com'), 'juego' => $this->getReference('juego-2')),
            array('nota' => 7, 'usuario' => $this->getReference('user@mail.com'), 'juego' => $this->getReference('juego-3'))
        );



        foreach ($puntuaciones as $puntuacion) {
            $entity = new Puntuacion();
            $entity->setNota($puntuacion['nota']);
            $entity->setJuego($puntuacion['juego']);
            $entity->setUsuario($puntuacion['usuario']);

            $manager->persist($entity);
        }

        $manager->flush();
    }

    public function getOrder() {
        return 3;
    }

}
