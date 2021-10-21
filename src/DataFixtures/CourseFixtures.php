<?php

namespace App\DataFixtures;

use App\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CourseFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        
        for($i=1; $i<6; $i++){
            $course = new Course();
            $course->setName("Course $i");
            $course->setDescription("Description $i");
            $course->setImage("course.png");
            $manager->persist($course);
        }

        $manager->flush();
    }
}
