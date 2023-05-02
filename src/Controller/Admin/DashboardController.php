<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Genre;
use App\Entity\Song;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('@EasyAdmin/page/content.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Groovy admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Songs', '', Song::class);
        yield MenuItem::linkToCrud('Artists', '', Artist::class);
        yield MenuItem::linkToCrud('Albums', '', Album::class);
        yield MenuItem::linkToCrud('Genres', '', Genre::class);
        yield MenuItem::linkToCrud('Users', '', User::class);
    }
}
