<?php

namespace App\Repository;

use App\Entity\EncounterResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EncounterResult>
 *
 * @method EncounterResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method EncounterResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method EncounterResult[]    findAll()
 * @method EncounterResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EncounterResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EncounterResult::class);
    }

//    /**
//     * @return EncounterResult[] Returns an array of EncounterResult objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EncounterResult
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
