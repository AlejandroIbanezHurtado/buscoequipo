<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/mis", name="mis")
*/
class MisController extends AbstractController
{
    /**
     * @Route("/equipos", name="misEquipos")
     */
    public function misEquipos(): Response
    {
        return $this->render('mis/equipos.html.twig', [
            'controller_name' => 'PrincipalController',
        ]);
    }

    /**
     * @Route("/partidos", name="misPartidos")
     */
    public function misPartidos(): Response
    {
        return $this->render('mis/equipos.html.twig', [
            'controller_name' => 'PrincipalController',
        ]);
    }

    /**
     * @Route("/torneos", name="misTorneos")
     */
    public function misTorneos(): Response
    {
        return $this->render('mis/equipos.html.twig', [
            'controller_name' => 'PrincipalController',
        ]);
    }
}
