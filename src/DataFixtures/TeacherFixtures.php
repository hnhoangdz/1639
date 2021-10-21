<?php

namespace App\DataFixtures;

use App\Entity\Teacher;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TeacherFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        for($i=1;$i<=10;$i++){
            $teacher = new Teacher();
            $teacher->setName("teacher $i");
            $teacher->setEmail("teacher$i@gmail.com");
            $teacher->setBirthday(\DateTime::createFromFormat('Y-m-d', '1990-05-08'));
            $teacher->setAvatar("teacher.jpg");
            $manager->persist($teacher);
        }
        $manager->flush();
    }
}
