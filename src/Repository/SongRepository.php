<?php

namespace App\Repository;

use App\Entity\Song;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Song|null find($id, $lockMode = null, $lockVersion = null)
 * @method Song|null findOneBy(array $criteria, array $orderBy = null)
 * @method Song[] findAll()
 * @method Song[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SongRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Song::class);
    }

    public function getLastFifteenSongs() {
        return $this->createQueryBuilder('s')
            ->setMaxResults(15)
            ->orderBy('s.id', 'DESC')
            ->setCacheable(true)
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getSongBySpotifyId(string $id) {
        return $this->createQueryBuilder('s')
            ->where('s.spotifyId = :id')
            ->setParameter('id', $id)
            ->setCacheable(true)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
