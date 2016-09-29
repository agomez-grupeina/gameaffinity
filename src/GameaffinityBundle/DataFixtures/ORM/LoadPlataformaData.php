<?php

namespace GameaffinityBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use GameaffinityBundle\Entity\Plataforma;
//Necesario para ordered fixture
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
//Necesario para pasar objetos como referencias en los fixtures
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadPlataformaData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $plataformas = array(
            array('nombre' => 'Playstation 3', 'fecha_lanzamiento' => new \DateTime('2009-02-14')),
            array('nombre' => 'Playstation 4', 'fecha_lanzamiento' => new \DateTime('2015-06-03')),
            array('nombre' => 'XboX', 'fecha_lanzamiento' => new \DateTime('2005-08-30')),
            array('nombre' => 'Xbox360', 'fecha_lanzamiento' => new \DateTime('2012-07-21')),
            array('nombre' => 'PC', 'fecha_lanzamiento' => new \DateTime('1990-01-12')),
            );

        foreach ($plataformas as $plataforma){
            $entity = new Plataforma();
            $entity->setNombre($plataforma['nombre']);
            $entity->setFechaLanzamiento($plataforma['fecha_lanzamiento']);
            $entity->setSlug($plataforma['nombre']);
            
            $this->addReference($entity->getSlug(), $entity);
            
            $manager->persist($entity);
        }

        $manager->flush();
    }

    public function getOrder() {
        return 1;
    }

}