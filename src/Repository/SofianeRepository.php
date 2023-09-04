<?php

namespace App\Repository;

use App\Entity\Sofiane;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sofiane>
 *
 * @method Sofiane|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sofiane|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sofiane[]    findAll()
 * @method Sofiane[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SofianeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sofiane::class);
    }

//    /**
//     * @return Sofiane[] Returns an array of Sofiane objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sofiane
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
