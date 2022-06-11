<?php

namespace App\Controller\api;

use stdClass;
use App\Entity\Equipo;
use App\Entity\Jugador;
use App\Entity\Partido;
use App\Entity\EquipoJugador;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PartidosController extends AbstractController
{
    /**
     * @Route("/api/obtenPartidosPag/{pagina}/{filas}/{perma}/{order}", name="obtenEquiposPermaPaginados")
     */
    public function obtenPartidosPag(ManagerRegistry $doctrine, int $pagina=1, int $filas=6, int $perma=1, string $order="desc"): Response
    {
        $obj = new stdClass();
        $repositoryPartido = $doctrine->getRepository(Partido::class);

        $todo = $repositoryPartido->obtenPartidosPaginados($pagina, $filas, $perma, $order);
        $obj->partidos = $todo->registros;
        $obj->total = $todo->n_total;

        return new Response(json_encode($obj));
    }
}
