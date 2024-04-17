<?php

// src/DataFixtures/UserFixture.php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // Example User 1
        $user1 = new User();
        $user1->setEmail('user1@example.com');
        $user1->setPassword($this->passwordEncoder->encodePassword($user1, 'password'));
        $user1->setRoles(['ROLE_USER']);
        $manager->persist($user1);

        // Example User 2
        $user2 = new User();
        $user2->setEmail('user2@example.com');
        $user2->setPassword($this->passwordEncoder->encodePassword($user2, 'password'));
        $user2->setRoles(['ROLE_USER']);
        $manager->persist($user2);

        // Example User 3
        $user3 = new User();
        $user3->setEmail('user3@example.com');
        $user3->setPassword($this->passwordEncoder->encodePassword($user3, 'password'));
        $user3->setRoles(['ROLE_USER']);
        $manager->persist($user3);

        $manager->flush();
    }
}
