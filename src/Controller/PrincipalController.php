<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrincipalController extends AbstractController
{
    /**
     * @Route("/index", name="app_principal")
     */
    public function index(): Response
    {
        return $this->render('index.html.twig', [
            'controller_name' => 'PrincipalController',
        ]);
    }
}
