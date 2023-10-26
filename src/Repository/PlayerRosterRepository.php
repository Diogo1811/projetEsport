<?php

namespace App\Repository;

use App\Entity\PlayerRoster;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlayerRoster>
 *
 * @method PlayerRoster|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlayerRoster|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlayerRoster[]    findAll()
 * @method PlayerRoster[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRosterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerRoster::class);
    }

//    /**
//     * @return PlayerRoster[] Returns an array of PlayerRoster objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PlayerRoster
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
