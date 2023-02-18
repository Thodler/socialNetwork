<?php

namespace App\DataFixtures;

use App\Entity\Type;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {

    }

    public function load(ObjectManager $manager): void
    {
        $typeMonument = (new Type())->setNom('monument');
        $typePays = (new Type())->setNom('pays');

        $manager->persist($typeMonument);
        $manager->persist($typePays);

        $admin = (new User())
            ->setEmail('admin@test.com')
            ->setPseudo('Jane Doe')
            ->setRoles(['ROLE_ADMIN'])
            ->setPhoto('default.jpg')
        ;
        $admin->setPassword($this->hasher->hashPassword($admin,'password'));

        $user = (new User())
            ->setEmail('user@test.com')
            ->setPseudo('Jhon Doe')
            ->setPhoto('default.jpg')
        ;
        $user->setPassword($this->hasher->hashPassword($user,'password'));

        $manager->persist($admin);
        $manager->persist($user);

        $manager->flush();
    }
}
