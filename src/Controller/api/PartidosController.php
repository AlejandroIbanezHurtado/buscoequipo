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

    /**
     * @Route("/api/obtenPartido/{id}", name="obtenPartido")
     */
    public function obtenPartido(ManagerRegistry $doctrine, $id): Response
    {
        $obj = new stdClass();
        $repositoryPartido = $doctrine->getRepository(Partido::class);
        $repositoryEquipo = $doctrine->getRepository(Equipo::class);

        $partido = $repositoryPartido->obtenPartido($id);
        $jugadores1 = $repositoryEquipo->obtenJugadoresPorEquipo($partido[0]['id_equipo_id']);
        $detalles = $repositoryPartido->obtenDetalle($id);
        $obj->partido = $partido;
        $obj->jugadores1 = $jugadores1;
        $obj->detalles = $detalles;
        if(isset($partido[1])) 
        {
            $jugadores2 = $repositoryEquipo->obtenJugadoresPorEquipo($partido[1]['id_equipo_id']);
            $obj->jugadores2 = $jugadores2;
        }
        $obj->resultados = $repositoryPartido->obtenResultados($id);
        // var_dump($obj->resultados);
        return new Response(json_encode($obj));
    }

    /**
     * @Route("/api/unirsePartidoPerma/{id}/{fecha_ini}/{fecha_fin}", name="unirsePartidoPerma")
     */
    public function unirsePartidoPerma(ManagerRegistry $doctrine, $id, $fecha_ini, $fecha_fin): Response
    {
        if(!isset($_SESSION)) session_start();
        $email = $_SESSION["_sf2_attributes"]["_security.last_username"];
        $obj = new stdClass();
        $repositoryPartido = $doctrine->getRepository(Partido::class);
        $repositoryJugador = $doctrine->getRepository(Jugador::class);
        $repositoryEquipo = $doctrine->getRepository(Equipo::class);

        $j = $repositoryJugador->findOneBy(['email' => $email])->getId();
        $capitan = $repositoryEquipo->findOneBy(['capitan' => $j, 'permanente' => 1]);
        $ocupado = $repositoryEquipo->obtenEquipoEntreFecha($j, $fecha_ini, $fecha_fin);
        $respuesta="No puedes";
        if($capitan!=null) //esta asociado a un equipo como capitan
        {
            if($ocupado==null) 
            {
                $respuesta=true;
            }
            else{
                $respuesta="Tienes un partido a esta hora";
            }
        }
        else{
            $respuesta="Debes de ser el capitán de un equipo para unirte al partido";
        }

        return new Response(json_encode($capitan));
    }
}
