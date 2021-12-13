<?php

namespace App\Repository;

use App\Entity\ExamQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExamQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExamQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExamQuestion[]    findAll()
 * @method ExamQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExamQuestion::class);
    }

    // /**
    //  * @return ExamQuestion[] Returns an array of ExamQuestion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExamQuestion
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
