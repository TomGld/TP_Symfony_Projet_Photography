<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(ProjectRepository $projectRepository, UserRepository $userRepository): Response
    {
        $title = 'Projects :';
        $projects = $projectRepository->findAllProjectsAndImages();
        $owner = $userRepository->find($projects[0]['owner_id']);
        
        return $this->render('home/index.html.twig', [

            'projects' => $projects,
            'title' => $title,
            'owner' => $owner
        ]);
    }
    
}
