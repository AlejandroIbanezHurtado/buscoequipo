<?php

namespace App\Controller\api;

use stdClass;
use App\Entity\Partido;
use App\Entity\PartidoEquipo;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    /**
     * @Route("/api/obtenProxPartidos", name="obtenProxPartidos")
     */
    public function obtenProxPartidos(ManagerRegistry $doctrine): Response
    {
        $obj = new stdClass();
        $repositoryPartido = $doctrine->getRepository(Partido::class);

        $obj->proxPartido = $repositoryPartido->obtenUltimoPartido();
        return new Response(json_encode($obj));
    }

    /**
     * @Route("/api/obtenIndex1", name="obtenIndex1")
     */
    public function obtenIndex1(ManagerRegistry $doctrine): Response
    {
        //Ultimo partido jugado en torneo -- torneo
        //Ultimos partidos (9) -- partido
    }

    /**
     * @Route("/api/obtenIndex2", name="obtenIndex2")
     */
    public function obtenIndex2(ManagerRegistry $doctrine): Response
    {
        //Equipos random (9) -- equipo
        //Pistas random (3) -- pista
    }
}