<?php

namespace App\Controller;

use App\Entity\EquipoJugador;
use App\Form\EquipoJugadorType;
use App\Repository\EquipoJugadorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/equipo/jugador")
 */
class EquipoJugadorController extends AbstractController
{
    /**
     * @Route("/", name="app_equipo_jugador_index", methods={"GET"})
     */
    public function index(EquipoJugadorRepository $equipoJugadorRepository): Response
    {
        return $this->render('equipo_jugador/index.html.twig', [
            'equipo_jugadors' => $equipoJugadorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_equipo_jugador_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EquipoJugadorRepository $equipoJugadorRepository): Response
    {
        $equipoJugador = new EquipoJugador();
        $form = $this->createForm(EquipoJugadorType::class, $equipoJugador);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $equipoJugadorRepository->add($equipoJugador, true);

            return $this->redirectToRoute('app_equipo_jugador_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('equipo_jugador/new.html.twig', [
            'equipo_jugador' => $equipoJugador,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_equipo_jugador_show", methods={"GET"})
     */
    public function show(EquipoJugador $equipoJugador): Response
    {
        return $this->render('equipo_jugador/show.html.twig', [
            'equipo_jugador' => $equipoJugador,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_equipo_jugador_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, EquipoJugador $equipoJugador, EquipoJugadorRepository $equipoJugadorRepository): Response
    {
        $form = $this->createForm(EquipoJugadorType::class, $equipoJugador);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $equipoJugadorRepository->add($equipoJugador, true);

            return $this->redirectToRoute('app_equipo_jugador_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('equipo_jugador/edit.html.twig', [
            'equipo_jugador' => $equipoJugador,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_equipo_jugador_delete", methods={"POST"})
     */
    public function delete(Request $request, EquipoJugador $equipoJugador, EquipoJugadorRepository $equipoJugadorRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$equipoJugador->getId(), $request->request->get('_token'))) {
            $equipoJugadorRepository->remove($equipoJugador, true);
        }

        return $this->redirectToRoute('app_equipo_jugador_index', [], Response::HTTP_SEE_OTHER);
    }
}
