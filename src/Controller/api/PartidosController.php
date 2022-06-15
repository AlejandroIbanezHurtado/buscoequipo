<?php

namespace App\Controller\api;

use stdClass;
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
        $repositoryPartidoEquipo = $doctrine->getRepository(PartidoEquipo::class);

        $partido = $repositoryPartido->obtenPartido($id);
        $jugadores1 = $repositoryEquipo->obtenJugadoresPorEquipo($partido[0]['id_equipo_id']);
        $detalles = $repositoryPartido->obtenDetalle($id);
        $id_equipo = $repositoryPartidoEquipo->findOneBy(['id_partido' => $id])->getIdEquipo();
        $permanente = $repositoryEquipo->findOneBy(['id' => $id_equipo])->getPermanente();
        $obj->partido = $partido;
        $obj->jugadores1 = $jugadores1;
        $obj->detalles = $detalles;
        $obj->permanente = $permanente;
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
     * @Route("/api/unirsePartidoPerma/{id}", name="unirsePartidoPerma")
     */
    public function unirsePartidoPerma(ManagerRegistry $doctrine, $id): Response
    {
        $entityManager = $doctrine->getManager();
        if(!isset($_SESSION)) session_start();
        $email = $_SESSION["_sf2_attributes"]["_security.last_username"];
        $obj = new stdClass();
        $equipoObj = new stdClass();
        $repositoryPartido = $doctrine->getRepository(Partido::class);
        $repositoryJugador = $doctrine->getRepository(Jugador::class);
        $repositoryEquipo = $doctrine->getRepository(Equipo::class);

        $j = $repositoryJugador->findOneBy(['email' => $email])->getId();
        $capitan = $repositoryEquipo->findOneBy(['capitan' => $j, 'permanente' => 1]);
        $equipo =$repositoryEquipo->findOneBy(['id' => $capitan->getId()]);
        $p = $repositoryPartido->findOneBy(['id' => $id]);
        $ocupado = $repositoryPartido->obtenEquipoEntreFecha($j, date_format($p->getFechaIni(),'Y-m-d H:i:s'), date_format($p->getFechaFin(),'Y-m-d H:i:s'),1);
        $respuesta="No puedes";
        $obj->clave=false;
        if(!empty($capitan)) //esta asociado a un equipo como capitan
        {
            if(empty($ocupado)) 
            {
                $pe = new PartidoEquipo();
                $pe->setIdEquipo($equipo);
                $pe->setIdPartido($p);
                $entityManager->persist($pe);
                $entityManager->flush();
                $equipoObj->escudo = $equipo->getEscudo();
                $equipoObj->nombre = $equipo->getNombre();
                $equipoObj->jugadores = $repositoryEquipo->obtenJugadoresPorEquipo($equipo->getId());
                $obj->equipo = $equipoObj;
                $obj->respuesta = "Tu equipo se unió al partido";
                $obj->clave=true;
            }
            else{
                $obj->respuesta="Tienes un partido a esta hora";
            }
        }
        else{
            $obj->respuesta="Debes de ser el capitán de un equipo para unirte al partido";
        }

        return new Response(json_encode($obj));
    }

    /**
     * @Route("/api/unirsePartidoTempo/{id}", name="unirsePartidoTempo")
     */
    public function unirsePartidoTempo(ManagerRegistry $doctrine, $id): Response
    {
        //primero miramos en partido_equipo si este partido tiene dos equipos
        //miramos fecha
            //si tiene un equipo
                //  tendremos que mostrar un modal de creacion rapida(escudo y nombre), se asigna capitan automaticamente
            //si tiene dos equipos, nos unimos directamente
        $entityManager = $doctrine->getManager();
        if(!isset($_SESSION)) session_start();
        $email = $_SESSION["_sf2_attributes"]["_security.last_username"];
        $obj = new stdClass();
        $equipoObj = new stdClass();
        $repositoryPartido = $doctrine->getRepository(Partido::class);
        $repositoryJugador = $doctrine->getRepository(Jugador::class);
        $repositoryEquipo = $doctrine->getRepository(Equipo::class);

        $j = $repositoryJugador->findOneBy(['email' => $email])->getId();
        $capitan = $repositoryEquipo->findOneBy(['capitan' => $j, 'permanente' => 1]);
        $equipo =$repositoryEquipo->findOneBy(['id' => $capitan->getId()]);
        $p = $repositoryPartido->findOneBy(['id' => $id]);
        $ocupado = $repositoryPartido->obtenEquipoEntreFecha($j, date_format($p->getFechaIni(),'Y-m-d H:i:s'), date_format($p->getFechaFin(),'Y-m-d H:i:s'),1);
        $respuesta="No puedes";
        $obj->clave=false;
        if(!empty($capitan)) //esta asociado a un equipo como capitan
        {
            if(empty($ocupado)) 
            {
                $pe = new PartidoEquipo();
                $pe->setIdEquipo($equipo);
                $pe->setIdPartido($p);
                $entityManager->persist($pe);
                $entityManager->flush();
                $equipoObj->escudo = $equipo->getEscudo();
                $equipoObj->nombre = $equipo->getNombre();
                $equipoObj->jugadores = $repositoryEquipo->obtenJugadoresPorEquipo($equipo->getId());
                $obj->equipo = $equipoObj;
                $obj->respuesta = "Tu equipo se unió al partido";
                $obj->clave=true;
            }
            else{
                $obj->respuesta="Tienes un partido a esta hora";
            }
        }
        else{
            $obj->respuesta="Debes de ser el capitán de un equipo para unirte al partido";
        }

        return new Response(json_encode($obj));
    }

    /**
     * @Route("/api/borraDePartido/{id}", name="borraDePartido")
     */
    public function borraDePartido(ManagerRegistry $doctrine, $id): Response
    {
        $entityManager = $doctrine->getManager();
        if(!isset($_SESSION)) session_start();
        $email = $_SESSION["_sf2_attributes"]["_security.last_username"];
        $obj = new stdClass();
        $repositoryPartido = $doctrine->getRepository(Partido::class);
        $repositoryJugador = $doctrine->getRepository(Jugador::class);
        $repositoryEquipo = $doctrine->getRepository(Equipo::class);
        $repositoryPartidoEquipo = $doctrine->getRepository(PartidoEquipo::class);

        $j = $repositoryJugador->findOneBy(['email' => $email])->getId();
        $capitan = $repositoryEquipo->findOneBy(['capitan' => $j, 'permanente' => 1]);
        $equipo =$repositoryEquipo->findOneBy(['id' => $capitan->getId()]);
        $p = $repositoryPartido->findOneBy(['id' => $id]);
        $ocupado = $repositoryPartido->obtenEquipoEntreFecha($j, date_format($p->getFechaIni(),'Y-m-d H:i:s'), date_format($p->getFechaFin(),'Y-m-d H:i:s'),1);
        $obj->clave=false;
        if(!empty($capitan)) //esta asociado a un equipo como capitan
        {
            $pe = $repositoryPartidoEquipo->findOneBy(['id_equipo' => $equipo->getId(), 'id_partido' => $p->getId()]);
            // $pe->setIdEquipo($equipo);
            // $pe->setIdPartido($p);
            $entityManager->remove($pe);
            $entityManager->flush();
            $obj->clave=true;
            $obj->perma=$equipo->getPermanente();
            $obj->respuesta="Te has borrado del partido";
        }
        else{
            $obj->respuesta="Debes de ser el capitán de un equipo para borrarte del partido";
        }

        return new Response(json_encode($obj));
    }

    /**
     * @Route("/api/mirarSiCapiEstaEnPartido/{id}", name="mirarSiCapiEstaEnPartido")
     */
    public function mirarSiCapiEstaEnPartido(ManagerRegistry $doctrine, $id): Response
    {
        $entityManager = $doctrine->getManager();
        if(!isset($_SESSION)) session_start();
        $email = $_SESSION["_sf2_attributes"]["_security.last_username"];
        $obj = new stdClass();
        $repositoryPartido = $doctrine->getRepository(Partido::class);
        $repositoryJugador = $doctrine->getRepository(Jugador::class);
        $repositoryEquipo = $doctrine->getRepository(Equipo::class);
        $repositoryPartidoEquipo = $doctrine->getRepository(PartidoEquipo::class);

        $existe = !empty($repositoryPartidoEquipo->mirarSiCapiEstaEnPartido($email,$id));

        return new Response(json_encode($existe));
    }
}
