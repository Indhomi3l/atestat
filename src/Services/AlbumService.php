<?php

namespace App\Services;

use App\Entity\Album;
use App\Entity\Artist;
use App\Lib\Enum\AlbumType;
use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use stdClass;

class AlbumService
{

    public function __construct(
        private readonly AlbumRepository $repository,
        private readonly EntityManagerInterface $_em
    ) {
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getAlbumOrCreate(stdClass $album, array $artists): Album
    {
        $existingAlbum = $this->getAlbumByNameAndArtist($album->id);
        if (null !== $existingAlbum) {
            return $existingAlbum;
        }

        $newAlbum = (new Album())
            ->setName($album->name)
            ->setSpotifyId($album->id)
            ->setAlbumType(AlbumType::from($album->album_type))
            ->setSpotifyApiUri($album->uri)
            ->setSpotifyUrl($album->href)
            ->setReleaseDate($album->release_date)
            ->setImages(new ArrayCollection($album->images));
        foreach($artists as $artist){
            $newAlbum->addArtist($artist);
        }

        $this->_em->persist($newAlbum);
        $this->_em->flush();

        return $newAlbum;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getAlbumByNameAndArtist(string $id): Album|null
    {
        return $this->repository->getAlbumBySpotifyId($id);
    }
}
