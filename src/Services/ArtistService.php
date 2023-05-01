<?php

namespace App\Services;

use App\Entity\Artist;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use stdClass;

class ArtistService
{
    public function __construct(
        private readonly ArtistRepository $repository,
        private readonly  EntityManagerInterface $_em
    ){}

    public function getArtistsOrCreate(array $artists): array {
        $persistedArtists = [];
        foreach ($artists as $artist) {
            $currentArtist = $this->getOrCreateSingleArtist($artist);
            $this->_em->persist($currentArtist);
            $persistedArtists[] = $currentArtist;
        }
        $this->_em->flush();
        return $persistedArtists;
    }

    private function getOrCreateSingleArtist(stdClass $artist) {
        $existingArtist = $this->repository->getArtistBySpotifyId($artist->id);
        if(null !== $existingArtist) {
            return $existingArtist;
        }

        return (new Artist())
            ->setSpotifyId($artist->id)
            ->setSpotifyUrl($artist->href)
            ->setName($artist->name);
    }
}
