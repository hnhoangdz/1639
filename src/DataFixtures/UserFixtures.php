<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $hasher;
    public function __construct (UserPasswordHasherInterface $hasher){
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager)
    {
        for($i=1;$i<=20;$i++){
            $user = new User();
            $user->setUsername("student$i@gmail.com");
            $user->setPassword($this->hasher->hashPassword($user,"123456"));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }
        for($i=1;$i<=10;$i++){
            $user = new User();
            $user->setUsername("teacher$i@gmail.com");
            $user->setPassword($this->hasher->hashPassword($user,"123456"));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }
        for($i=1;$i<=3;$i++){
            $user = new User();
            $user->setUsername("admin$i@gmail.com");
            $user->setPassword($this->hasher->hashPassword($user,"123456"));
            $user->setRoles(['ROLE_ADMIN']);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
