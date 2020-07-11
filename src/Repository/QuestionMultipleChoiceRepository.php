<?php

namespace App\Repository;

use App\Entity\QuestionMultipleChoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QuestionMultipleChoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionMultipleChoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionMultipleChoice[]    findAll()
 * @method QuestionMultipleChoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionMultipleChoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionMultipleChoice::class);
    }

    // /**
    //  * @return QuestionMultipleChoice[] Returns an array of QuestionMultipleChoice objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuestionMultipleChoice
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
