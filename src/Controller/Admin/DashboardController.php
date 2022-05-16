<?php

namespace App\Controller\Admin;

use App\Entity\Pista;
use App\Entity\Alerta;
use App\Entity\Equipo;
use App\Entity\Torneo;
use App\Entity\Jugador;
use App\Entity\Partido;
use App\Entity\Valoracion;
use App\Entity\TorneoEquipo;
use App\Entity\TorneoPartido;
use App\Entity\DetallePartido;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Admin\JugadorCrudController;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // redirect to some CRUD controller
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(JugadorCrudController::class)->generateUrl());

        // you can also redirect to different pages depending on the current user
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // you can also render some template to display a proper Dashboard
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Buscoequipo');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Inicio', 'fa fa-home');
        yield MenuItem::linkToCrud('Alertas', 'fas fa-bell', Alerta::class);
        yield MenuItem::linkToCrud('Jugadores', 'fas fa-running', Jugador::class);
        yield MenuItem::linkToCrud('Partidos', 'fas fa-futbol', Partido::class);
        yield MenuItem::linkToCrud('Detalles partidos', 'fas fa-chart-bar', DetallePartido::class);
        yield MenuItem::linkToCrud('Equipos', 'fas fa-users', Equipo::class);
        yield MenuItem::linkToCrud('Pistas', 'fas fa-map', Pista::class);
        yield MenuItem::linkToCrud('Torneos', 'fas fa-trophy', Torneo::class);
        yield MenuItem::linkToCrud('Valoraciones', 'fas fa-comments', Valoracion::class);
        yield MenuItem::linkToCrud('Torneos-Equipos', 'fas fa-edit', TorneoEquipo::class);
        yield MenuItem::linkToCrud('Torneos-Partidos', 'fas fa-edit', TorneoPartido::class);
    }
}
