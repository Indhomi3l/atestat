<?php

namespace App\Repository;

use App\Entity\Album;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Album|null find($id, $lockMode = null, $lockVersion = null)
 * @method Album|null findOneBy(array $criteria, array $orderBy = null)
 * @method Album[] findAll()
 * @method Album[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlbumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Album::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getAlbumBySpotifyId(string $id) {
        return $this->createQueryBuilder('al')
            ->where('al.spotifyId = :id')
            ->setParameter('id', $id)
            ->setCacheable(true)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
