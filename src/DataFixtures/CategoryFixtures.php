<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $category = new Category();
        $category->setName("Business");
        $category->setDescription("Business is a digital media company and B2B");
        $manager->persist($category);

        $category = new Category();
        $category->setName("Computing");
        $category->setDescription("Computing is any goal-oriented activity requiring, benefiting from, or creating computing machinery");
        $manager->persist($category);

        $category = new Category();
        $category->setName("Graphic Design");
        $category->setDescription("Graphic design is the profession and academic discipline whose activity consists in projecting");
        $manager->persist($category);
        $manager->flush();
    }
}
