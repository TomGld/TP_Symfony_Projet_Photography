<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $this->loadUsers($manager);
        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager): void
    {

        //On crée un tableau avec les infos des users
        $array_users =
            [
                [
                    'email' => 'admin@admin.com',
                    'password' => 'admin',
                    'firstname' => 'nameAdmin',
                    'lastname' => 'lastnameAdmin',
                    //Format datetime (naissance : 2006-01-01)
                    'age' => '2006-01-01',
                    'city' => 'Paris',
                    'country' => 'France',
                    'roles' => ['ROLE_ADMIN']
                ],
                [
                    'email' => 'user@user.com',
                    'password' => 'user',
                    'firstname' => 'nameUser',
                    'lastname' => 'lastnameUser',
                    //Format datetime (naissance : 2007-01-01)
                    'age' => '2007-01-01',
                    'city' => 'Perpignan',
                    'country' => 'France',
                    'roles' => ['ROLE_USER']
                ],
            ];

        foreach ($array_users as $key => $value) {
            //on instancie un user
            $user = new User();
            $user->setEmail($value['email']);
            $user->setPassword($value['password']);
            $user->setPassword($this->encoder->hashPassword($user, $value['password']));
            $user->setFirstname($value['firstname']);
            $user->setLastname($value['lastname']);
            $user->setAge(new \DateTime($value['age']));
            $user->setCity($value['city']);
            $user->setCountry($value['country']);
            $user->setRoles($value['roles']);
            $manager->persist($user);

            //Définition des références
            $this->addReference('user_' . $key + 1, $user);
        }
    }
}
