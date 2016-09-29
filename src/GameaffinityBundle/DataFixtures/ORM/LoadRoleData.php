<?php

namespace GameaffinityBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use GameaffinityBundle\Entity\Role;
//Necesario para ordered fixture
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
//Necesario para pasar objetos como referencias en los fixtures
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadRoleData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $names = array('ROLE_ADMIN', 'ROLE_MANAGER', 'ROLE_USER');

        foreach ($names as $name) {
            $role = new Role();
            $role->setNombre($name);
            $manager->persist($role);
            $this->addReference($name, $role);
        }

        $manager->flush();
    }

    public function getOrder() {
        return 1;
    }

}
