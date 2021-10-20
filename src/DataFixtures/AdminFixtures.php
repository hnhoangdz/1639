<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AdminFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        for($i=1;$i<=3;$i++){
            $admin = new Admin();
            $admin->setName("admin $i");
            $admin->setEmail("admin$i@gmail.com");
            $admin->setBirthday(\DateTime::createFromFormat('Y-m-d', '2001-05-08'));
            $manager->persist($admin);
        }
        $manager->flush();
    }
}
