<?php

namespace App\Controller\api;

use stdClass;
use App\Entity\Pista;
use App\Entity\Equipo;
use App\Entity\Jugador;
use App\Entity\Partido;
use App\Entity\EquipoJugador;
use App\Entity\PartidoEquipo;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PistasController extends AbstractController
{
    /**
     * @Route("/api/obtenPistas", name="obtenPistas")
     */
    public function obtenPartidosPag(ManagerRegistry $doctrine): Response
    {
        $obj = new stdClass();
        $repositoryPista = $doctrine->getRepository(Pista::class);
        $obj->pistas =  $repositoryPista->obtenPistasIndex(1000,"id");
        return new Response(json_encode($obj));
    }

}
