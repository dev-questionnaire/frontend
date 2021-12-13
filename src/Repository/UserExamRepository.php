<?php

namespace App\Repository;

use App\Entity\UserExam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserExam|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserExam|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserExam[]    findAll()
 * @method UserExam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserExamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserExam::class);
    }
}
