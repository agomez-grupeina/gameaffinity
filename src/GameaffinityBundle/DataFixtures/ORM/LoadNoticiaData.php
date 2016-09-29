<?php

namespace GameaffinityBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use GameaffinityBundle\Entity\Noticia;
//Necesario para ordered fixture
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
//Necesario para pasar objetos como referencias en los fixtures
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadNoticiaData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $noticias = array(
            array('titulo' => 'Noticia 1', 'imagen' => 'noticia1.jpg', 'texto' => 'dfgdgndunidfjgn dfgfijfgbfgbf fg', 'autor' => $this->getReference('manager@mail.com')),
            array('titulo' => 'Noticia 2', 'imagen' => 'noticia2.jpg', 'texto' => 'dfgdgndulunidfjgn dfgfijfgbfgbf fg', 'autor' => $this->getReference('manager@mail.com')),
            array('titulo' => 'Noticia 3', 'imagen' => 'noticia3.jpg', 'texto' => 'dfgdgndunidfjgn dfgfijfgbfgbf fg', 'autor' => $this->getReference('manager@mail.com')),
            array('titulo' => 'Noticia 4', 'imagen' => 'noticia4.jpg', 'texto' => 'dfgdgndunidfjgn dfgfijfgbfgbf fg', 'autor' => $this->getReference('admin@mail.com')),
            array('titulo' => 'Noticia 5', 'imagen' => 'noticia5.jpg', 'texto' => 'dfgdgndunidfjgn dfgfijfgbfgbf fg', 'autor' => $this->getReference('admin@mail.com')),
            );

        foreach ($noticias as $noticia){
            $entity = new Noticia();
            $entity->setTitulo($noticia['titulo']);
            $entity->setImagen($noticia['imagen']);
            $entity->setSlug();
            $entity->setTexto($noticia['texto']);
            $entity->setAutor($noticia['autor']);
            
            $manager->persist($entity);
        }

        $manager->flush();
    }

    public function getOrder() {
        return 2;
    }

}