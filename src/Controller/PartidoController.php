<?php

namespace App\Controller;

use App\Entity\Equipo;
use App\Entity\Jugador;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PartidoController extends AbstractController
{
    /**
     * @Route("/partidos/permanentes", name="partidosPermanentes")
     */
    public function partidosPermanentes(ManagerRegistry $doctrine): Response
    {
        return $this->render('partido/listado.html.twig', [
            'controller_name' => 'PartidoController',
            'perma' => 1,
        ]); 
    }

    /**
     * @Route("/partidos/temporales", name="partidosTemporales")
     */
    public function partidosTemporales(ManagerRegistry $doctrine): Response
    {
        return $this->render('partido/listado.html.twig', [
            'controller_name' => 'PartidoController',
            'perma' => 0,
        ]); 
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/partido/permanente/{id}", name="partidoPermanenteId")
     */
    public function partidoPermanenteId(ManagerRegistry $doctrine, $id): Response
    {
        return $this->render('partido/unirsePartido.html.twig', [
            'controller_name' => 'PartidoController',
        ]); 
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/partido/temporal/{id}", name="partidoTemporalId")
     */
    public function partidoTemporalId(ManagerRegistry $doctrine, $id): Response
    {
        return $this->render('partido/unirsePartido.html.twig', [
            'controller_name' => 'PartidoController',
        ]); 
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/crear/partido/permanente", name="crearPartidoPermanente")
     */
    public function crearPartidoPermanente(ManagerRegistry $doctrine): Response
    {
        return $this->render('partido/crearPartido.html.twig', [
            'controller_name' => 'PartidoController',
            'perma' => 1
        ]); 
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/crear/partido/temporal", name="crearPartidoTemporal")
     */
    public function crearPartidoTemporal(ManagerRegistry $doctrine): Response
    {
        return $this->render('partido/crearPartido.html.twig', [
            'controller_name' => 'PartidoController',
            'perma' => 0
        ]); 
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/detallePartido/{id}", name="detallePartido")
     */
    public function detallePartido(ManagerRegistry $doctrine, $id): Response
    {
        return $this->render('partido/detallePartido.html.twig', [
            'controller_name' => 'PartidoController',
            'perma' => 0
        ]); 
    }
}
