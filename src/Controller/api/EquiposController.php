<?php

namespace App\Controller\api;

use stdClass;
use App\Entity\Equipo;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EquiposController extends AbstractController
{
    /**
     * @Route("api/obtenEquiposPermaPaginados/{pagina}/{filas}", name="obtenEquiposPermaPaginados")
     */
    public function obtenEquiposPermaPaginados(ManagerRegistry $doctrine, int $pagina=1, int $filas=12): Response
    {
        $obj = new stdClass();
        $repositoryEquipo = $doctrine->getRepository(Equipo::class);

        $obj->equipos = $repositoryEquipo->obtenEquiposPermaPaginados($pagina,$filas);
        $obj->total_equipos = count($repositoryEquipo->obtenEquiposPerma());
        return new Response(json_encode($obj));
    }
}
