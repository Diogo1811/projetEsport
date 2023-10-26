<?php

namespace App\Repository;

use App\Entity\TournamentResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TournamentResult>
 *
 * @method TournamentResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method TournamentResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method TournamentResult[]    findAll()
 * @method TournamentResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournamentResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TournamentResult::class);
    }

//    /**
//     * @return TournamentResult[] Returns an array of TournamentResult objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TournamentResult
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
