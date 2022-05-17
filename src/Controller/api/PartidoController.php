<?php

namespace App\Controller\api;

use stdClass;
use App\Entity\Partido;
use App\Entity\PartidoEquipo;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PartidoController extends AbstractController
{
    /**
     * @Route("/api/obtenUltimoPartido", name="obtenUltimoPartido")
     */
    public function obtenUltimoPartido(ManagerRegistry $doctrine): Response
    {
        $obj = new stdClass();
        $repositoryPartido = $doctrine->getRepository(Partido::class);

        $obj->partido = $repositoryPartido->obtenUltimoPartido();
        return new Response(json_encode($obj));
    }
}