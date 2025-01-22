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
     * Méthode qui récupère tous les projets et les images associées depuis la table image_project, les notes, 
     * @return array
     */
    public function findAllProjectsAndImages(): array
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();
        $query = $qb->select([
            'p.id',
            'p.name',
            'p.dateStart',
            'p.dateEnd',
            'p.description',
            'o.id AS owner_id',
        ])->from(Project::class, 'p')
        ->join('p.owner', 'o')
        ->getQuery();

        return $query->getResult();
        
    }



}
