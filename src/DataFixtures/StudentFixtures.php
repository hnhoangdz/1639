<?php

namespace App\DataFixtures;

use App\Entity\Student;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class StudentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        for($i=1;$i<=20;$i++){
            $student = new Student();
            $student->setName("student $i");
            $student->setEmail("student$i@gmail.com");
            $student->setBirthday(\DateTime::createFromFormat('Y-m-d', '2001-05-08'));
            $manager->persist($student);
        }
        $manager->flush();
    }
}
