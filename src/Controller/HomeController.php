<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * Fonction pour afficher l'index
     *
     * @return Response
     */
    
    #[Route('/', name: 'app_home', methods:['GET'])]
    // methods pour la sécurité donc pour éviter les post ou put sur les routes

    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
