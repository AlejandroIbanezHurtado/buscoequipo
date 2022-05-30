<?php

namespace App\Controller\api;

use stdClass;
use App\Entity\Equipo;
use App\Entity\Jugador;
use App\Entity\EquipoJugador;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
                $respuesta = "Este equipo está lleno";
            }
            else{
                if(count($boolEquipo)!=0)
                {
                    $respuesta = "Ya perteneces a un equipo";
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
                    $respuesta = "Te las unido al equipo ".$e->getNombre();
                }
            }
            return new Response($respuesta);
        }
        else
        {
            //REQUISITOS PARA UNIRSE A EQUIPO TEMPO
            $email = $_SESSION["_sf2_attributes"]["_security.last_username"];
            $respuesta = $this->render('equipo/unirseEquipo.html.twig', [
                'controller_name' => 'EquipoController',
            ]);
            $equipo = $repositoryEquipo->obtenEquipoCompleto(1,$id);
            $boolEquipo = $repositoryJugador->obtenJugadorEquipo($email);
            
            $equipo = $repositoryPartido->obtenEquipoTempoEntreFecha($id_partido, $fecha_ini, $fecha_fin); //devuelve el equipo disponible o null
            if(intval($equipo)>=12) $respuesta = $this->render('equipo/equipolleno.html.twig', ['controller_name' => 'EquipoController', 'texto' => "Lo sentimos, este equipo está lleno",]);
            //if entre fechaini y fecha_fin coincide con otra fecha_ini y fecha_fin respuesta = $this->render('equipo/equipolleno.html.twig', ['controller_name' => 'EquipoController', 'texto' => "Lo sentimos, tienes un partido a esa hora",]);
            // select * from partido WHERE NOT ((date_add('${fecha_ini}', INTERVAL 1 MICROSECOND) BETWEEN fecha_ini AND fecha_fin) OR (date_sub('${fecha_fin}', INTERVAL 1 MICROSECOND) BETWEEN fecha_ini AND fecha_fin)) and id = ${id_partido}


            //UNIRSE A EQUIPO GENERICO
            $id_equipo = $_POST['id_equipo'];
            $email = $_SESSION["_sf2_attributes"]["_security.last_username"];

            $e = $repositoryEquipo->find($id_equipo);
            $j = $repositoryJugador->findOneBy(['email' => $email]);
            $ej = new EquipoJugador();
            $ej->setEquipo($e);
            $ej->setJugador($j);
            $entityManager->persist($ej);
            $entityManager->flush();
            return new Response(json_encode($ej));
        }

        
        
    }
}
