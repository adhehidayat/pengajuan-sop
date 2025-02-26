<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setNama('Admin 1');


        $user1->setPassword($this->passwordHasherEncrypt($user1, '123123'));
        $user1->setNik('111111');
        $user1->setTelepon('0811111111111');
        $user1->setRoles(['ROLE_ADMIN']);
        $user1->setEmail('admin1@admin.com');
        $user1->setAlamat('admin 1');

        $manager->persist($user1);

        $user2 = new User();
        $user2->setNama('Admin 2');

        $user2->setPassword($this->passwordHasherEncrypt($user2, '123123'));
        $user2->setNik('222222');
        $user2->setTelepon('0822222222222');
        $user2->setRoles(['ROLE_OPERATOR']);
        $user2->setEmail('admin2@admin.com');
        $user2->setAlamat('admin 2');

        $manager->persist($user2);

        $user3 = new User();
        $user3->setNama('Admin 3');

        $user3->setPassword($this->passwordHasherEncrypt($user3, '123123'));
        $user3->setNik('333333');
        $user3->setTelepon('0833333333333');
        $user3->setRoles(['ROLE_USER']);
        $user3->setEmail('admin3@admin.com');
        $user3->setAlamat('admin 3');

        $manager->persist($user3);


    }

    public function passwordHasherEncrypt(User $user, $password): string
    {
        return $this->passwordHasher->hashPassword($user, $password);
    }
}
