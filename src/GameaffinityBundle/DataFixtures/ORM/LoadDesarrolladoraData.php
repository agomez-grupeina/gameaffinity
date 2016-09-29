<?php

namespace GameaffinityBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use GameaffinityBundle\Entity\Desarrolladora;
//Necesario para ordered fixture
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
//Necesario para pasar objetos como referencias en los fixtures
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadDesarrolladorData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $desarrolladoras = array(
            array('nombre' => 'Konami', 'fecha_fundacion' => new \DateTime('1982-02-10')),
            array('nombre' => 'Microsoft', 'fecha_fundacion' => new \DateTime('1986-06-27')),
            array('nombre' => 'Sony', 'fecha_fundacion' => new \DateTime('1991-05-15')),
            array('nombre' => 'Square Enix', 'fecha_fundacion' => new \DateTime('1987-07-26')),
            array('nombre' => 'Mierdasoft', 'fecha_fundacion' => new \DateTime('1994-10-28')),
        );

        foreach ($desarrolladoras as $desarrollador) {
            $entity = new Desarrolladora();
            $entity->setNombre($desarrollador['nombre']);
            $entity->setFechaFundacion($desarrollador['fecha_fundacion']);
            $entity->setSlug($desarrollador['nombre']);
            
            $this->addReference($entity->getSlug(), $entity);
            
            $manager->persist($entity);
        }

        $manager->flush();
    }

    public function getOrder() {
        return 1;
    }

}