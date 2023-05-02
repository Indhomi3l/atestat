<?php

namespace App\Controller;

use App\Services\AlbumService;
use App\Services\ArtistService;
use App\Services\SongService;
use JsonException;
use Psr\Cache\InvalidArgumentException;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Throwable;

class IndexController extends AbstractController
{

    public function __construct(
        private readonly SpotifyWebAPI $spotifyWebAPI,
        private readonly CacheInterface $cache,
        private readonly ArtistService $artistService,
        private readonly AlbumService $albumService,
        private readonly SongService $songService
    ) {
    }

    #[Route(path: '/', name: 'app_index_home')]
    public function home(): Response
    {
        return $this->render('home.html.twig', [
            'active' => 'home',
            'songs' => $this->songService->getSongsForHomePage()
        ]);
    }


    #[Route(path: '/discover', name: 'app_index_discover')]
    public function discover(): Response
    {
        return $this->render('discover.html.twig', [
            'active' => 'discover',
            'songs' => $this->songService->getAll()
        ]);
    }

    #[Route(path: '/song/{id}', name: 'app_index_song')]
    public function songPage(int $id): Response
    {
        return $this->render('singleSong.html.twig', [
            'active' => null,
            'song' => $this->songService->getById($id)
        ]);
    }

    #[Route(path: '/about', name: 'app_index_about')]
    public function about(): Response
    {
        return $this->render('about.html.twig', [
            'active' => 'about'
        ]);
    }

    #[Route(path: '/api/song/save', name: 'app_save_song')]
    public function saveSong(Request $request): JsonResponse
    {
        try {
            $rawSong = $this->getSong($request);
        } catch (Throwable $e) {
            return new JsonResponse($e->getMessage(), 500);
        }
        try {
            $artists = $this->artistService->getArtistsOrCreate($rawSong->artists);
            $album = $this->albumService->getAlbumOrCreate($rawSong->album, $artists);
            $song = $this->songService->update($rawSong, $artists, $album);
        } catch (Throwable $e) {
            return new JsonResponse($e->getMessage(), 500);
        }

        return new JsonResponse($song->getId(), 201);
    }

    /**
     * @throws InvalidArgumentException
     * @throws JsonException
     */
    private function getSong(Request $request): stdClass
    {
        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true, 512, JSON_THROW_ON_ERROR);
        $name = $data['name'];
        $artist = $data['artist'];
        $key = str_replace(' ', '_', strtolower($name) . '_' . strtolower($artist));
        $items = $this->cache->get($key, function () use ($name, $artist) {
            $result = $this->spotifyWebAPI->search(
                $artist . ' ' . $name,
                ['track'],
                [
                    'limit' => 5,
                    'offset' => 0,
                    'market' => 'RO'
                ]
            );
            return $result->tracks->items;
        });
        return $items[$data['selection']];
    }

}
