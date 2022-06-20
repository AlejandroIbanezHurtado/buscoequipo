<?php

namespace App\Controller\api;

use DateTime;
use stdClass;
use App\Entity\Pista;
use App\Entity\Equipo;
use App\Entity\Jugador;
use App\Entity\Partido;
use App\Entity\EquipoJugador;
use App\Entity\PartidoEquipo;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Validator\Constraints\DateTime;
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

    /**
     * @Route("/api/crearPartidoPermanente", name="apicrearPartidoPermanente")
     */
    public function crearPartidoPermanente(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        if(!isset($_SESSION)) session_start();
        $email = $_SESSION["_sf2_attributes"]["_security.last_username"];
        $obj = new stdClass();
        $formato = 'Y-m-d H:i:s';
        $id_pista = $_POST['pista'];
        $fecha_ini = $_POST['fecha_ini'];
        $fecha_ini = new \DateTime();
        $fecha_ini->setTimestamp((intval($_POST['fecha_ini'])/1000)+7200);
        $fecha_fin = new \DateTime();
        $fecha_fin->setTimestamp((intval($_POST['fecha_fin'])/1000)+10800);
        

        $repositoryPartido = $doctrine->getRepository(Partido::class);
        $repositoryJugador = $doctrine->getRepository(Jugador::class);
        $repositoryEquipo = $doctrine->getRepository(Equipo::class);
        $repositoryPista = $doctrine->getRepository(Pista::class);
        $pista = $repositoryPista->findOneBy(['id' => $id_pista]);
        $j = $repositoryJugador->findOneBy(['email' => $email])->getId();
        $capitan = $repositoryEquipo->findOneBy(['capitan' => $j, 'permanente' => 1]);
        $equipo =$repositoryEquipo->findOneBy(['id' => $capitan->getId()]);
        $ocupado = $repositoryPartido->obtenEquipoEntreFecha($j, $fecha_ini->format('Y-m-d H:i:s'), $fecha_fin->format('Y-m-d H:i:s'),1);
        $respuesta="No puedes";
        $obj->clave=false;
        if(!empty($capitan)) //esta asociado a un equipo como capitan
        {
            if(empty($ocupado)) 
            {
                $p = new Partido();
                $p->setFechaIni($fecha_ini);
                $p->setFechaFin($fecha_fin);
                $p->setPista($pista);
                $entityManager->persist($p);
                $entityManager->flush();
                $pe = new PartidoEquipo();
                $pe->setIdEquipo($equipo);
                $pe->setIdPartido($p);
                $entityManager->persist($pe);
                $entityManager->flush();
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
     * @Route("/api/crearPartidoTemporal", name="apicrearPartidoTemporal")
     */
    public function crearPartidoTemporal(ManagerRegistry $doctrine, ValidatorInterface $validator): Response
    {
        $obj = new stdClass();
        $equipoObj = new stdClass();
        if(empty($_SESSION))
        {
            session_start();
        }
        $correo = $_SESSION['_sf2_attributes']['_security.last_username'];

        $repositoryJugador = $doctrine->getRepository(Jugador::class);
        $repositoryEquipo = $doctrine->getRepository(Equipo::class);
        $repositoryEquipoJugador = $doctrine->getRepository(EquipoJugador::class);
        $repositoryPartidoEquipo = $doctrine->getRepository(PartidoEquipo::class);
        $repositoryPartido = $doctrine->getRepository(Partido::class);
        $repositoryPista = $doctrine->getRepository(Pista::class);
        $j = $repositoryJugador->findOneBy(['email' => $correo])->getId();
        $id_pista = $_POST['pista'];
        $pista = $repositoryPista->findOneBy(['id' => $id_pista]);
        
        $fecha_ini = $_POST['fecha_ini'];
        $fecha_ini = new \DateTime();
        $fecha_ini->setTimestamp((intval($_POST['fecha_ini'])/1000)+7200);
        $fecha_fin = new \DateTime();
        $fecha_fin->setTimestamp((intval($_POST['fecha_fin'])/1000)+10800);
  
        $j = $repositoryJugador->findOneBy(array('email' => $correo));
        $ocupado = $repositoryPartido->obtenEquipoEntreFecha($j->getId(), $fecha_ini->format('Y-m-d H:i:s'), $fecha_fin->format('Y-m-d H:i:s'),1);

        $e = new Equipo();
        $e->setNombre($_POST["nombre"]);
        $e->setPermanente(false);
        $e->setCapitan($j);

        if(isset($_FILES['file']))
        {
            $nombre = time().rand(1,99999).$_FILES['file']['name'];
            move_uploaded_file($_FILES["file"]["tmp_name"], "bd/".$nombre);
            $e->setEscudo($nombre);
        }
        
        $errores = $validator->validate($e);
        $array = [];
        foreach ($errores as &$valor) {
            $array[] = $valor->getMessage();
        }
        $respuesta=$array;
        if(count($array)==0) $respuesta="EL PARTIDO SE HA CREADO CORRECTAMENTE";
        $obj->respuesta = $respuesta;
        $ej = new EquipoJugador();
        $ej->setEquipo($e);
        $ej->setJugador($j);
        if(count($errores)==0)
        {
            if(empty($ocupado))
            {
                $repositoryEquipo->add($e,true);
                $repositoryEquipoJugador->add($ej,true);
                $p = new Partido();
                $p->setFechaIni($fecha_ini);
                $p->setFechaFin($fecha_fin);
                $p->setPista($pista);
                $repositoryPartido->add($p,true);
                $pe = new PartidoEquipo();
                $pe->setIdPartido($p);
                $pe->setIdEquipo($e);
                $repositoryPartidoEquipo->add($pe,true);
                $equipoObj->escudo = $e->getEscudo();
                $equipoObj->nombre = $e->getNombre();
                $equipoObj->jugadores = $repositoryEquipo->obtenJugadoresPorEquipo($e->getId());
                $obj->detalle = $equipoObj;
            }
            else{
                $obj->respuesta = "TIENES PARTIDOS EN ESTA FECHA";
            }
        }
        
        
        
        
        return new Response(json_encode($obj));
    }

}
