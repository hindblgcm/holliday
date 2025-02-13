<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\LeaveRequest;
use App\Entity\Enum\StatusEnum;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class AppFixtures extends Fixture
{
    public function __construct(
                private PasswordHasherFactoryInterface $passwordHasherFactory,
            ) {
            }
        
public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $user->setEmail('test@test.com');
        $user->setPassword('password');
        $manager->persist($user);
        $leaverequest = new LeaveRequest();
        $leaverequest->setReason('Vacation');
        $leaverequest->setUser($user);
        $leaverequest->setStartDate(new \DateTime('2024-01-01'));
        $leaverequest->setEndDate(new \DateTime('2024-01-05'));
        $leaverequest->setStatus(StatusEnum::submitted);
        $manager->persist($leaverequest);
        $manager->persist($user);
        $manager->flush();
    }
}
