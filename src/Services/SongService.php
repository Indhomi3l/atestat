<?php

namespace App\Services;

use App\Entity\Album;
use App\Entity\Song;
use App\Repository\SongRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use stdClass;

class SongService
{

    public function __construct(
        private readonly SongRepository $repository,
        private readonly EntityManagerInterface $_em
    ) {
    }

    public function getSongsForHomePage()
    {
        $songs =  $this->repository->getLastFifteenSongs();
        $extra = count($songs) %5;
        if($extra != 0) {
            array_splice($songs, $extra * -1 );
        }
        return $songs;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function update(stdClass $rawSong, array $artists, Album $album)
    {
        $song = $this->repository->getSongBySpotifyId($rawSong->id);
        if (null === $song) {
            $song = (new Song())
                ->setName($rawSong->name)
                ->setSpotifyUrl($rawSong->href)
                ->setDuration($rawSong->duration_ms)
                ->setExplicit($rawSong->explicit)
                ->setSpotifyId($rawSong->id)
                ->setSpotifyApiUri($rawSong->uri);
        }
        foreach ($artists as $artist) {
            $song->addArtist($artist);
        }

        $song->setAlbum($album);

        $this->_em->persist($song);
        $this->_em->flush();
        return $song;
    }

    public function getAll(): array
    {
        $songs =  $this->repository->findAll();
        $extra = count($songs) %5;
        if($extra != 0) {
            array_splice($songs, $extra * -1 );
        }
        return $songs;
    }

    public function getById(int $id): Song
    {
        return $this->repository->find($id);
    }
}
