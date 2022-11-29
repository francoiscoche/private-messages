<?php

namespace App\DataFixtures;

use App\Entity\Messages;
use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Mime\Message;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        // $manager->flush();

        $faker = Factory::create('fr_FR');
        $users = [];


        // Users
        for ($i=0; $i < 20; $i++) {
            $user = new User();

            $user->setEmail($faker->email())
                ->setPassword($this->hasher->hashPassword($user, 'password'))
                ->setRoles(['ROLE_USER']);
                
            $users[] = $user;    
            $manager->persist($user);
        }

        // Messages
        for ($i=0; $i < 200; $i++) { 
            $message = new Messages();

            $message->setTitle($faker->text(30))
                ->setText($faker->text(254))
                ->setIsRead(mt_rand(0,1))
                ->setTrashbin(false)
                ->setSender($users[mt_rand(0, count($users) - 1)])
                ->setRecipient($users[mt_rand(0, count($users) - 1)]);
            
            $manager->persist($message);
        }

        $manager->flush();
    }
}
