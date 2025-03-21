<?php

namespace App\Repository;

use App\Entity\CategoryQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoryQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryQuestion[]    findAll()
 * @method CategoryQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryQuestion::class);
    }

    // /**
    //  * @return CategoryQuestion[] Returns an array of CategoryQuestion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategoryQuestion
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
