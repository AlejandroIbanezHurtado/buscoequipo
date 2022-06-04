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

class EquiposController extends AbstractController
{
    /**
     * @Route("api/obtenEquiposPermaPaginados/{pagina}/{filas}/{perma}", name="obtenEquiposPermaPaginados")
     */
    public function obtenEquiposPermaPaginados(ManagerRegistry $doctrine, int $pagina=1, int $filas=12, int $perma=1): Response
    {
        $obj = new stdClass();
        $repositoryEquipo = $doctrine->getRepository(Equipo::class);

        $obj->equipos = $repositoryEquipo->obtenEquiposPermaPaginados($pagina,$filas,$perma);
        $obj->total_equipos = count($repositoryEquipo->obtenEquiposPerma());
        return new Response(json_encode($obj));
    }

    /**
     * @Route("api/obtenEquipo/{id}", name="obtenEquipo")
     */
    public function obtenEquipo(ManagerRegistry $doctrine, $id): Response
    {
        $obj = new stdClass();
        $repositoryEquipo = $doctrine->getRepository(Equipo::class);
        $obj->equipos = $repositoryEquipo->obtenEquipo($id);
        return new Response(json_encode($obj));
    }

    /**
     * @Route("api/obtenJugadoresPorEquipo/{id}", name="obtenJugadoresPorEquipo")
     */
    public function obtenJugadoresPorEquipo(ManagerRegistry $doctrine, $id): Response
    {
        $obj = new stdClass();
        $repositoryEquipo = $doctrine->getRepository(Equipo::class);
        $jugadores = $repositoryEquipo->obtenJugadoresPorEquipo($id);
        for($i=0;$i<count($jugadores);$i++)
        {
            if($jugadores[$i]["imagen"]==null) $jugadores[$i]["imagen"] = "user.png";
        }
        $obj->jugadores = $jugadores;
        return new Response(json_encode($obj));
    }

    /**
     * @Route("api/insertaJugadorEnEquipo/{id}", name="insertaJugadorEnEquipo")
     */
    public function insertaJugadorEnEquipo(ManagerRegistry $doctrine, $id): Response
    {
        $obj = new stdClass();
        $entityManager = $doctrine->getManager();
        $repositoryEquipo = $doctrine->getRepository(Equipo::class);
        $repositoryJugador = $doctrine->getRepository(Jugador::class);
        $repositoryPartido = $doctrine->getRepository(Partido::class);
        $repositoryEquipoJugador = $doctrine->getRepository(EquipoJugador::class);

        //VEMOS SI ES PERMANENTE O NO
        $boolPerma = $repositoryEquipo->findOneBy(['id' => $id])->getPermanente();
        if($boolPerma==1)
        {
            //REQUISITOS PARA UNIRSE A EQUIPO PERMA
            $email = $_SESSION["_sf2_attributes"]["_security.last_username"];
            $equipo = $repositoryEquipo->obtenEquipoCompleto(1,$id);
            $boolEquipo = $repositoryJugador->obtenJugadorEquipo($email);
            if(intval($equipo)>=12) 
            {
                $obj->clave = false;
                $obj->respuesta = "Este equipo está lleno";
            }
            else{
                if(count($boolEquipo)!=0)
                {
                    $obj->clave = false;
                    $obj->respuesta = "Ya perteneces a un equipo";
                }
                else{
                    $id_equipo = $_POST['id_equipo'];
                    $email = $_SESSION["_sf2_attributes"]["_security.last_username"];

                    $e = $repositoryEquipo->find($id_equipo);
                    $j = $repositoryJugador->findOneBy(['email' => $email]);
                    $ej = new EquipoJugador();
                    $ej->setEquipo($e);
                    $ej->setJugador($j);
                    $entityManager->persist($ej);
                    $entityManager->flush();
                    $obj->clave = true;
                    $obj->respuesta = "Te las unido al equipo ".$e->getNombre();
                }
            }
            return new Response(json_encode($obj));
        }
    }

    /**
     * @Route("api/borraJugadorEnEquipo/{id}", name="borraJugadorEnEquipo")
     */
    public function borraJugadorEnEquipo(ManagerRegistry $doctrine, $id): Response
    {
        $entityManager = $doctrine->getManager();
        $repositoryEquipo = $doctrine->getRepository(Equipo::class);
        $repositoryJugador = $doctrine->getRepository(Jugador::class);
        $repositoryEquipoJugador = $doctrine->getRepository(EquipoJugador::class);

        $id_equipo = $_POST['id_equipo'];
        $email = $_SESSION["_sf2_attributes"]["_security.last_username"];
        $id_jugador = $repositoryJugador->findOneBy(['email' => $email])->getId();
        $id = $repositoryEquipoJugador->encontrarPorJE(strval($id_jugador), strval($id_equipo))[0]['id'];
        $ej = $repositoryEquipoJugador->find($id);
        $repositoryEquipoJugador->remove($ej, true);
            
        return new Response("Te has borrado del equipo");
        
    }

    /**
     * @Route("api/borraJugadorEnEquipoCapitan/{id}", name="borraJugadorEnEquipoCapitan")
     */
    public function borraJugadorEnEquipoCapitan(ManagerRegistry $doctrine, $id): Response
    {
        $obj = new stdClass();
        $entityManager = $doctrine->getManager();
        $repositoryEquipo = $doctrine->getRepository(Equipo::class);
        $repositoryJugador = $doctrine->getRepository(Jugador::class);
        $repositoryEquipoJugador = $doctrine->getRepository(EquipoJugador::class);

        $id_equipo = $_POST['id_equipo'];
        $email = $_SESSION["_sf2_attributes"]["_security.last_username"];
        $id_jugador = $repositoryJugador->findOneBy(['email' => $email])->getId();
        $id = $repositoryEquipoJugador->encontrarPorJE(strval($id_jugador), strval($id_equipo))[0]['id'];
        $ej = $repositoryEquipoJugador->find($id);
        $repositoryEquipoJugador->remove($ej, true);
        $ids_juga = $repositoryEquipo->obtenOtroCapitan(strval($id_jugador),strval($id_equipo));
        if(count($ids_juga)!=0)
        {
            $id_nuevo_capitan = $ids_juga[0]['id'];
            $equipo = $repositoryEquipo->find(strval($id_equipo));
            $jugador = $repositoryJugador->find(strval($id_nuevo_capitan));
            $equipo->setCapitan($jugador);
            $repositoryEquipo->add($equipo,true);
            $obj->mensaje = "Te has borrado del equipo actual y tu puesto de capitán se ha cedido a otra persona";
            $obj->id_nuevo_capitan = $id_nuevo_capitan;
        }
        else{
            $equipo = $repositoryEquipo->find(strval($id_equipo));
            $repositoryEquipo->remove($equipo,true);
            $obj->mensaje = "El equipo se ha borrado ya que su capitán ha abandonado";
            $obj->id_nuevo_capitan = null;
        }
        
        return new Response(json_encode($obj));
        
    }

    /**
     * @Route("/api/creaEquipoPerma", name="creaEquipoPerma")
     */
    public function creaEquipoPerma(ManagerRegistry $doctrine, ValidatorInterface $validator): Response
    {
        if(empty($_SESSION))
        {
            session_start();
        }
        $correo = $_SESSION['_sf2_attributes']['_security.last_username'];

        $repositoryJugador = $doctrine->getRepository(Jugador::class);
        $repositoryEquipo = $doctrine->getRepository(Equipo::class);
        $repositoryEquipoJugador = $doctrine->getRepository(EquipoJugador::class);
        $boolEquipo = $repositoryJugador->obtenJugadorEquipo($correo);
        if(count($boolEquipo)!=0)
        {
            $respuesta = "Ya perteneces a un equipo";
        }
        else
        {
            $j = $repositoryJugador->findOneBy(array('email' => $correo));
            $e = new Equipo();
            $e->setNombre($_POST["nombre"]);
            $e->setPermanente(true);
            $e->setCapitan($j);
    
            if(isset($_FILES['file']))
            {
                $nombre = time().rand(1,99999).$_FILES['file']['name'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "bd/".$nombre);
                $e->setEscudo($nombre);
            }
    
            $ej = new EquipoJugador();
            $ej->setEquipo($e);
            $ej->setJugador($j);
            $errores = $validator->validate($e);
            if(count($errores)==0)
            {
                $repositoryEquipo->add($e,true);
                $repositoryEquipoJugador->add($ej,true);
            }
            $array = [];
            foreach ($errores as &$valor) {
                $array[] = $valor->getMessage();
            }
            $respuesta=$array;
            if(count($array)==0) $respuesta="EL EQUIPO SE HA CREADO CORRECTAMENTE";
        }
        return new Response(json_encode($respuesta));
    }
}
