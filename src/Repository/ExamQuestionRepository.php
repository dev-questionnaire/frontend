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
}
