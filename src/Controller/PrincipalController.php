<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrincipalController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(): Response
    {
        return $this->render('index.html.twig', [
            'controller_name' => 'PrincipalController',
        ]);
    }

    /**
     * @Route("/equipos", name="equipos")
     */
    public function equipos(): Response
    {
        return $this->render('team.html.twig', [
            'controller_name' => 'PrincipalController',
        ]);
    }

    /**
     * @Route("/partidos", name="partidos")
     */
    public function partidos(): Response
    {
        return $this->render('matches.html.twig', [
            'controller_name' => 'PrincipalController',
        ]);
    }

    /**
     * @Route("/torneos", name="torneos")
     */
    public function torneos(): Response
    {
        return $this->render('news.html.twig', [
            'controller_name' => 'PrincipalController',
        ]);
    }

    /**
     * @Route("/pistas", name="pistas")
     */
    public function pistas(): Response
    {
        return $this->render('about.html.twig', [
            'controller_name' => 'PrincipalController',
        ]);
    }

    /**
     * @Route("/base", name="base")
     */
    public function base(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'PrincipalController',
        ]);
    }
}
