<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProjectRepository $projectRepository, UserRepository $userRepository): Response
    {
        $title = 'Tous les projets :';
        $projects = $projectRepository->findAllProjectsImagesCollaborators();
        
        

        return $this->render('home/index.html.twig', [

            'projects' => $projects,
            'title' => $title,
        ]);
    }



    #[Route('/detail/{id}', name: 'app_detail')]
    public function projectById(ProjectRepository $projectRepository, int $id): Response
    {
        //On récupère le jeux avec ses notes et son age
        $project = $projectRepository->findProjectImagesCollaboratorsById($id);
        
        //
        
        //On récupère le titre
        $title = $project['name'];


        return $this->render('home/detail.html.twig', [
            'project' => $project,
            'title' => $title
        ]);
    }
}
