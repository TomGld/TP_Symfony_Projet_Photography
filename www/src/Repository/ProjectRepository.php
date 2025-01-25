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



    /**
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
            'i.id AS image_id',
            'i.imagePath AS image_path',
        ])
            ->from(Project::class, 'p')
            ->leftJoin('p.images', 'i')  
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
                    'images' => [], // Initialisation du tableau des images
                    'collaborators' => [], // Initialisation du tableau des collaborateurs
                ];
            }

            // Ajouter un collaborateur uniquement s'il existe et n'a pas déjà été ajouté
            if (!empty($result['collaborator_id']) && !in_array($result['collaborator_id'], array_column($projects[$projectId]['collaborators'], 'id'))) {
                $projects[$projectId]['collaborators'][] = [
                    'id' => $result['collaborator_id'],
                    'firstname' => $result['collaborator_firstname'],
                    'lastname' => $result['collaborator_lastname'],
                ];
            }
        
            // Ajouter une image uniquement si elle n'a pas déjà été ajoutée
            if (!empty($result['image_id']) && !in_array($result['image_id'], array_column($projects[$projectId]['images'], 'id'))) {
                $projects[$projectId]['images'][] = [
                    'id' => $result['image_id'],
                    'imagePath' => $result['image_path'],
                ];
            }
        }
        return array_values($projects); // Retourner les projets sous forme de tableau indexé
    }



    /**
     * Méthode qui récupère un projet, ses images et collaborateurs associés depuis la table project, image_project, project_user,
     * @param int $id
     * @return array|null
     */
    public function findProjectImagesCollaboratorsById(int $id): ?array
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
            'i.id AS image_id',
            'i.imagePath AS image_path',
        ])
            ->from(Project::class, 'p')
            ->leftJoin('p.images', 'i')
            ->leftJoin('p.collaborator', 'c')    // Jointure gauche pour inclure les collaborateurs (s'il y en a)
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        $results = $query->getResult();

        if (empty($results)) {
            return null; // Retourner null si aucun projet n'est trouvé
        }

        // Structurer les résultats pour regrouper les collaborateurs par projet
        $project = [
            'id' => $results[0]['project_id'],
            'name' => $results[0]['project_name'],
            'dateStart' => $results[0]['project_date_start'],
            'dateEnd' => $results[0]['project_date_end'],
            'description' => $results[0]['project_description'],
            'images' => [],
            'collaborators' => [],
        ];

        foreach ($results as $result) {
            // Ajouter un collaborateur uniquement s'il existe et n'a pas déjà été ajouté
            if (!empty($result['collaborator_id']) && !in_array($result['collaborator_id'], array_column($project['collaborators'], 'id'))) {
                $project['collaborators'][] = [
                    'id' => $result['collaborator_id'],
                    'firstname' => $result['collaborator_firstname'],
                    'lastname' => $result['collaborator_lastname'],
                ];
            }

            // Ajouter une image uniquement si elle n'a pas déjà été ajoutée
            if (!empty($result['image_id']) && !in_array($result['image_id'], array_column($project['images'], 'id'))) {
                $project['images'][] = [
                    'id' => $result['image_id'],
                    'imagePath' => $result['image_path'],
                ];
            }
        }

        return $project; // Retourner le projet sous forme de tableau
    }









    


}
