<?php

namespace App\Repository;

use App\Entity\BlogCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BlogCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogCategory[]    findAll()
 * @method BlogCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogCategory::class);
    }

    // /**
    //  * @return BlogCategory[] Returns an array of BlogCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BlogCategory
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findAllWithBlogs()
    {
        return $this->createQueryBuilder('bc')
            ->addSelect('b')
            ->addSelect('COUNT(b) AS blogsCount', 'bc')
            ->leftJoin('bc.blogs', 'b')
            ->where('b.public = :public')
            ->setParameter('public', true)
            ->addOrderBy('blogsCount', 'DESC')
            ->addOrderBy('bc.title', 'ASC')
            ->groupBy('bc')
            ->getQuery()
            ->getResult();
    }

    public function findAll()
    {
        return $this->findBy([], ['title' => 'ASC']);
    }
}
