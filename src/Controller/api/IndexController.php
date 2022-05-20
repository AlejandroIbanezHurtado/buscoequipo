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
        $obj = new stdClass();
        $repositoryPartido = $doctrine->getRepository(Partido::class);

        $id = $repositoryPartido->obtenUltimoPartidoJugadoTorneo();
        $obj->nombre_torneo = $id[0]["nombre"];
        $obj->tipo = $id[0]["tipo"];
        $obj->datos_equipos = $repositoryPartido->obtenDatosEquiposPorId($id[0]["id"]);
        $obj->goles_partido = $repositoryPartido->obtenGolesPartidoPorId($id[0]["id"]);

        //Ultimos partidos (9) -- partido
        $obj->partidos = $repositoryPartido->obtenPartidosIndex();

        return new Response(json_encode($obj));
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