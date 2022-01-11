<?php

namespace App\Repository;

use App\Entity\UserQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserQuestion[]    findAll()
 * @method UserQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserQuestion::class);
    }
}
