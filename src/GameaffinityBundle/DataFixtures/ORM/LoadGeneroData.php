<?php

namespace GameaffinityBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use GameaffinityBundle\Entity\Genero;
//Necesario para ordered fixture
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
//Necesario para pasar objetos como referencias en los fixtures
use Doctrine\Common\DataFixtures\AbstractFixture;

use GameaffinityBundle\Util\Utils;

class LoadGeneroData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $names = array('Estrategia', 'Disparos Primera Persona', 'Disparos Tercera Persona',
            'Rol', 'Acción', 'Aventuras');

        foreach ($names as $name) {
            $genero = new Genero();
            $genero->setNombre($name);
            $genero->setSlug();
            $manager->persist($genero);
            
            $this->addReference(Utils::getSlug($name), $genero);
        }

        $manager->flush();
    }

    public function getOrder() {
        return 1;
    }

}