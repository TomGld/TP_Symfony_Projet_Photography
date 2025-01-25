<?php

namespace App\Repository;

use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    // /**
    //  * A TERMINER
    //  * Méthode qui permet de récupérer tous les données du projet : image_path, name, date_start, date_end, description, owner_id, note_id
    //  * @param int $id
    //  * @return array
    //  */
    // public function findAllDataProject(int $id): array
    // {
    //     $entityManager = $this->getEntityManager();
    //     $qb = $entityManager->createQueryBuilder();
    //     $query = $qb->select([
    //         'p.name',
    //         'p.date_start',
    //         'p.date_end',
    //         'p.description',
    //         'p.owner_id',
    //         'p.note_id',
    //         'i.image_path',


    //     ])->from(Project::class, 'p')
    //     ->join ('p.image', 'i')
    //     ->where('p.id = :id')
    //     ->setParameter('id', $id)
    //     ->getQuery();

    //     return $query->getResult();
        
    // }


    /**
    * MANQUE LES IMAGES
     * Méthode qui récupère tous les projets, images et collaborators associées depuis la table project, image_project, project_user, 
     * @return array
     */
    public function findAllProjectsImagesCollaborators(): array
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();

        // Sélectionner les champs nécessaires, y compris les collaborateurs
        $query = $qb->select([
            'p.id AS project_id',
            'p.name AS project_name',
            'p.dateStart AS project_date_start',
            'p.dateEnd AS project_date_end',
            'p.description AS project_description',
            'c.id AS collaborator_id',
            'c.firstname AS collaborator_firstname',
            'c.lastname AS collaborator_lastname',
        ])
            ->from(Project::class, 'p')
            ->leftJoin('p.collaborator', 'c')    // Jointure gauche pour inclure les collaborateurs (s'il y en a)
            ->getQuery();

        $results = $query->getResult();

        // Structurer les résultats pour regrouper les collaborateurs par projet
        $projects = [];
        foreach ($results as $result) {
            $projectId = $result['project_id'];

            // Si le projet n'est pas encore ajouté, on l'initialise
            if (!isset($projects[$projectId])) {
                $projects[$projectId] = [
                    'id' => $result['project_id'],
                    'name' => $result['project_name'],
                    'dateStart' => $result['project_date_start'],
                    'dateEnd' => $result['project_date_end'],
                    'description' => $result['project_description'],
                    'collaborators' => [], // Initialisation du tableau des collaborateurs
                ];
            }

            // Ajouter un collaborateur uniquement s'il existe
            if (!empty($result['collaborator_id'])) {
                $projects[$projectId]['collaborators'][] = [
                    'id' => $result['collaborator_id'],
                    'firstname' => $result['collaborator_firstname'],
                    'lastname' => $result['collaborator_lastname'],
                ];
            }
        }

        return array_values($projects); // Retourner les projets sous forme de tableau indexé
    }

}
