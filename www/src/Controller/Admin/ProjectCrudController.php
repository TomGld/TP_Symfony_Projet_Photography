<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Note;
use App\Entity\Project;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProjectCrudController extends AbstractCrudController
{

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Project::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom du projet'),
            //Editeur de texte qui ne permet pas de mettre des balises html dans le texte
            TextareaField::new('description', 'Description du projet')
                ->setLabel('Description') // Nom du champ
                ->setHelp('Aucun formatage, uniquement du texte brut'),
            DateField::new('dateStart', 'Date de commencement')
            ->setFormat('dd-MM-yyyy'),
            // champ pour la date de création et la date d'update
            // seront caché dans le form et on passera les données grace a un persister
            DateField::new('dateEnd', 'Date de fin')
                ->setFormat('dd-MM-yyyy'),
            IntegerField::new('note.mediaNote', 'Note Média')
            ->setHelp('Entrez une note pour les médias'),
            IntegerField::new('note.userNote', 'Note Utilisateur')
            ->setHelp('Entrez une note pour les utilisateurs'),
            AssociationField::new('images', 'Image(s) associée(s)')
            ->setFormTypeOptions(['by_reference' => false])
            ->setHelp('Choisissez une ou plusieurs image(s) à ce projet ou téléversez une nouvelle image'),



        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Project) {
            return;
        }

        $user = $this->security->getUser();
        $entityInstance->setOwner($user);

        // Ajouter l'utilisateur à la collection des collaborateurs
        if (!$entityInstance->getCollaborator()->contains($user)) {
            $entityInstance->addCollaborator($user);  // Ajoute l'utilisateur à la relation ManyToMany
        }

        // Lier les images au projet et à l'utilisateur connecté
        foreach ($entityInstance->getImages() as $image) {
            if ($image->getUser() === null) {
                $image->setUser($user); // Associe l'image à l'utilisateur
            }
            $image->addProject($entityInstance); // Lie l'image au projet
            $entityManager->persist($image);
        }

        // Ajoute les détenteurs des images associées comme collaborateurs
        foreach ($entityInstance->getImages() as $image) {
            if ($image instanceof Image) {
                $imageOwner = $image->getUser(); // Récupère le propriétaire de l'image
                if ($imageOwner && !$entityInstance->getCollaborator()->contains($imageOwner)) {
                    $entityInstance->addCollaborator($imageOwner);
                }
            }
        }

        // L'objet Note est déjà créé automatiquement par le constructeur de Project.
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Project) {
            return;
        }

        // Ajoute les détenteurs des images associées comme collaborateurs
        foreach ($entityInstance->getImages() as $image) {
            if ($image instanceof Image) {
                $imageOwner = $image->getUser(); // Récupère le propriétaire de l'image
                if ($imageOwner && !$entityInstance->getCollaborator()->contains($imageOwner)) {
                    $entityInstance->addCollaborator($imageOwner);
                }
            }
        }
        parent::updateEntity($entityManager, $entityInstance);
    }

}
