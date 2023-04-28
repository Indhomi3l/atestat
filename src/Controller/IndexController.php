<?php

namespace App\Controller;

use App\Services\SongService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route(path: '/', name: 'app_index_home')]
    public function home(SongService $service): Response
    {
        return $this->render('home.html.twig', [
            'active' => 'home',
            'songs' => $service->getSongsForHomePage()
        ]);
    }

    #[Route(path: '/discover', name: 'app_index_discover')]
    public function discover(): Response
    {
        return $this->render('discover.html.twig', [
            'active' => 'discover'
        ]);
    }

    #[Route(path: '/about', name: 'app_index_about')]
    public function about(): Response
    {
        return $this->render('about.html.twig', [
            'active' => 'about'
        ]);
    }
}
