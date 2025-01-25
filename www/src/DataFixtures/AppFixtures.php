<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Note;
use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadNotes($manager);
        $this->loadProjects($manager);
        $this->loadImages($manager);
        $this->loadImageProjects($manager);
        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager): void
    {
        $array_users = [
            [
                'email' => 'admin@admin.com',
                'password' => 'admin',
                'firstname' => 'nameAdmin',
                'lastname' => 'lastnameAdmin',
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
                'age' => '2007-01-01',
                'city' => 'Perpignan',
                'country' => 'France',
                'roles' => ['ROLE_USER']
            ],
        ];

        foreach ($array_users as $key => $value) {
            $user = new User();
            $user->setEmail($value['email']);
            $user->setPassword($this->encoder->hashPassword($user, $value['password']));
            $user->setFirstname($value['firstname']);
            $user->setLastname($value['lastname']);
            $user->setAge(new \DateTime($value['age']));
            $user->setCity($value['city']);
            $user->setCountry($value['country']);
            $user->setRoles($value['roles']);
            $manager->persist($user);

            // Définir une référence pour chaque utilisateur
            $this->addReference('user_' . ($key + 1), $user);
        }
    }

    public function loadNotes(ObjectManager $manager): void
    {
        $array_notes = [
            [
                'media_note' => 15,
                'user_note' => 19,
            ],
            [
                'media_note' => 17,
                'user_note' => 12,
            ],
        ];

        foreach ($array_notes as $key => $value) {
            $note = new Note();
            $note->setMediaNote($value['media_note']);
            $note->setUserNote($value['user_note']);
            $manager->persist($note);

            // Définir une référence pour chaque note
            $this->addReference('note_' . ($key + 1), $note);
        }
    }

    public function loadProjects(ObjectManager $manager): void
    {
        $array_projects = [
            [
                'name' => 'Montagnes dorées',
                'description' => 'Un projet sur plusieurs jours, où l\'on perçoit la beauté de paysages visibles par tous.',
                'date_start' => '2024-12-22',
                'date_end' => '2024-12-22',
                'owner_id' => 1,
                'note_id' => 1,
                'collaborator_id' => [1],
            ],
            [
                'name' => 'Liberté au plus haut',
                'description' => 'un ciel doré, un soleil couchant, accompagné d\'oiseaux volant en coeur.',
                'date_start' => '2025-01-06',
                'date_end' => '2025-01-06',
                'owner_id' => 2,
                'note_id' => 2,
                //Ajout de collaborateur plusieurs collaborator (1, 2)

                'collaborator_id' => [1, 2],
            ],
        ];

        foreach ($array_projects as $key => $value) {
            $project = new Project();
            $project->setName($value['name']);
            $project->setDescription($value['description']);
            $project->setDateStart(new \DateTime($value['date_start']));
            $project->setDateEnd(new \DateTime($value['date_end']));
            $project->setOwner($this->getReference('user_' . $value['owner_id'], User::class));
            $project->setNote($this->getReference('note_' . $value['note_id'], Note::class));
            // Ajouter les collaborateurs
            foreach ($value['collaborator_id'] as $collaborator_id) {
                $project->addCollaborator($this->getReference('user_' . $collaborator_id, User::class));
            }
            $manager->persist($project);

            // Définir une référence pour chaque projet
            $this->addReference('project_' . ($key + 1), $project);
        }
    }

    public function loadImages(ObjectManager $manager): void
    {
        $array_images = [
            [
                'image_path' => 'IMG_0832.jpeg',
                'user_id' => 1,
            ],
            [
                'image_path' => 'IMG_0664_EDITED1.jpg',
                'user_id' => 2,
            ],
        ];

        foreach ($array_images as $key => $value) {
            $image = new Image();
            $image->setImagePath($value['image_path']);
            $image->setUser($this->getReference('user_' . $value['user_id'], User::class));
            $manager->persist($image);

            // Définir une référence pour chaque image
            $this->addReference('image_' . ($key + 1), $image);
        }
    }

    public function loadImageProjects(ObjectManager $manager): void
    {
        $array_image_projects = [
            [
                'image_id' => 1,
                'project_id' => 1,
            ],
            [
                'image_id' => 2,
                'project_id' => 2,
            ],
        ];

        foreach ($array_image_projects as $value) {
            $image = $this->getReference('image_' . $value['image_id'], Image::class);
            $project = $this->getReference('project_' . $value['project_id'], Project::class);

            // Ajouter l'image au projet
            $project->addImage($image);
            $manager->persist($project);
        }
    }

}
