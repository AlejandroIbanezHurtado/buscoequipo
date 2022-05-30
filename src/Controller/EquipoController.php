<?php

namespace App\Controller;

use App\Entity\Equipo;
use App\Entity\Jugador;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EquipoController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/unete/equipo/{id}", name="unirseEquipo")
     */
    public function unirseEquipo(ManagerRegistry $doctrine,$id): Response
    {
        // $email = $_SESSION["_sf2_attributes"]["_security.last_username"];
        $respuesta = $this->render('equipo/unirseEquipo.html.twig', [
            'controller_name' => 'EquipoController',
        ]);
        // $repositoryEquipo = $doctrine->getRepository(Equipo::class);
        // $repositoryJugador = $doctrine->getRepository(Jugador::class);
        // $equipo = $repositoryEquipo->obtenEquipoCompleto(1,$id);
        // $boolEquipo = $repositoryJugador->obtenJugadorEquipo($email);
        // if(intval($equipo)>=12) $respuesta = $this->render('equipo/equipolleno.html.twig', ['controller_name' => 'EquipoController', 'texto' => "Lo sentimos, este equipo está lleno",]);
        // if(count($boolEquipo)!=0) $respuesta = $this->render('equipo/equipolleno.html.twig', ['controller_name' => 'EquipoController', 'texto' => "Lo sentimos, ya perteneces a un equipo",]);
        return $respuesta;
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/unete/equipo/perma/{id}", name="unirseEquipoPerma")
     */
    public function unirseEquipoPerma(ManagerRegistry $doctrine,$id): Response
    {
        $email = $_SESSION["_sf2_attributes"]["_security.last_username"];
        $respuesta = $this->render('equipo/unirseEquipo.html.twig', [
            'controller_name' => 'EquipoController',
        ]);
        $repositoryEquipo = $doctrine->getRepository(Equipo::class);
        $repositoryJugador = $doctrine->getRepository(Jugador::class);
        $equipo = $repositoryEquipo->obtenEquipoCompleto(1,$id);
        $boolEquipo = $repositoryJugador->obtenJugadorEquipo($email);
        if(intval($equipo)>=12) $respuesta = $this->render('equipo/equipolleno.html.twig', ['controller_name' => 'EquipoController', 'texto' => "Lo sentimos, este equipo está lleno",]);
        if(count($boolEquipo)!=0) $respuesta = $this->render('equipo/equipolleno.html.twig', ['controller_name' => 'EquipoController', 'texto' => "Lo sentimos, ya perteneces a un equipo",]);
        return $respuesta;
    }
}
