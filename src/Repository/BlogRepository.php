<?php

namespace App\Repository;

use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Blog;
use App\Entity\BlogCategory;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    // /**
    //  * @return Blog[] Returns an array of Blog objects
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
    public function findOneBySomeField($value): ?Blog
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findAllPublicWithSearchAndRelations($search = false)
    {
        $cat = false;
        $tag = false;
        $searched = false;
        $queryBuilder = $this->createQueryBuilder('b')
            ->leftJoin('b.category', 'bc')
            ->addSelect('bc')
            ->leftJoin('b.comments', 'c')
            ->addSelect('c')
            ->leftJoin('b.likes', 'l')
            ->addSelect('l')
            ->leftJoin('b.tags', 't')
            ->addSelect('t')
            ->where('b.public = :public')
            ->setParameter('public', true);

        if ($search['catSlug']) {
            $cat = $this->getEntityManager()->getRepository(BlogCategory::class)->findOneBy(['slug' => $search['catSlug']]);

            if (!$cat) {
                throw $this->createNotFoundException('The category cannot be found.');
            }

            $searched = [
                'type' => 'cat',
                'value' => $cat->getTitle(),
            ];

            $queryBuilder->andWhere('b.category = :cat')
                ->setParameter('cat', $cat->getId());
        } else if ($search['tagSlug']) {
            $tag = $this->getEntityManager()->getRepository(Tag::class)->findOneBy(['slug' => $search['tagSlug']]);

            if (!$tag) {
                throw $this->createNotFoundException('The tag cannot be found.');
            }

            $searched = [
                'type' => 'tag',
                'value' => $tag->getName(),
            ];

            $queryBuilder->andWhere(':tag MEMBER OF b.tags')
                ->setParameter('tag', $tag);
        }

        if ($search['search']) {
            $queryBuilder->andWhere('b.title LIKE :search OR b.slug LIKE :search OR b.content LIKE :search')
                ->setParameter('search', '%' . $search['search'] . '%');
        }

        $queryBuilder->orderBy('b.id', 'DESC');

        return [
            'query' => $queryBuilder->getQuery(),
            'searched' => $searched,
        ];
    }

    public function findAllPublicWithAdvancedSearchAndRelations(Object $request, $searchOptionsHelper)
    {
        $orderBy = $searchOptionsHelper->getValue('orderBy', $request->get('orderBy'));
        $queryBuilder = $this->createQueryBuilder('b');
        // ->addSelect('l, bc, c, t')
        // ->leftJoin('b.likes', 'l')
        // ->leftJoin('b.category', 'bc')
        // ->leftJoin('b.comments', 'c')
        // ->leftJoin('b.tags', 't')

        if (isset($orderBy['join'])) {
            $queryBuilder
                ->addSelect('COUNT(' . $orderBy['alias'] . ') AS ' . $orderBy['join'] . 'Count', 'b')
                ->leftJoin('b.' . $orderBy['join'], $orderBy['alias']);
        }

        $queryBuilder
            ->where('b.public = :public')
            ->setParameter('public', true);

        if ($request) {
            if ($request->get('hasImg')) {
                $queryBuilder->andWhere('b.img IS NOT NULL')
                    ->andWhere('b.img != :img')
                    ->setParameter('img', '');
            }

            if ($request->get('title')) {
                $queryBuilder->andWhere('b.title LIKE :search')
                    ->setParameter('search', '%' . $request->get('title') . '%');
            }

            if ($request->get('author')) {
                $users = $this->getEntityManager()->getRepository(User::class)->createQueryBuilder('u')
                    ->andWhere('u.username LIKE :author')
                    ->setParameter('author', '%' . $request->get('author') . '%')
                    ->getQuery()
                    ->getResult();

                $queryBuilder->andWhere('b.user IN (:users)')
                    ->setParameter('users', $users);
            }

            if ($request->get('writtenAfter')) {
                $queryBuilder->andWhere('b.createdAt >= :writtenAfter')
                    ->setParameter('writtenAfter', $request->get('writtenAfter') . ' 00:00:00');
            }

            if ($request->get('writtenBefore')) {
                $queryBuilder->andWhere('b.createdAt <= :writtenBefore')
                    ->setParameter('writtenBefore', $request->get('writtenBefore') . ' 23:59:59');
            }

            if ($request->get('likesFrom') || $request->get('likesTo')) {
                $queryHelper = $this->createQueryBuilder('b')
                    ->leftJoin('b.likes', 'l')
                    ->addSelect('l')
                    ->select('b.id')
                    ->where('b.public = :public')
                    ->setParameter('public', true)
                    ->groupBy('b');

                if ($request->get('likesFrom')) {
                    $queryHelper->having('COUNT(l) >= :likesFrom')
                        ->setParameter('likesFrom', $request->get('likesFrom'));
                }

                if ($request->get('likesTo')) {
                    $queryHelper->andHaving('COUNT(l) <= :likesTo')
                        ->setParameter('likesTo', $request->get('likesTo'));
                }

                $ids = $queryHelper->getQuery()->getResult();

                $queryBuilder->andWhere('b.id IN (:ids)')
                    ->setParameter('ids', array_column($ids, 'id'));
            }

            if ($request->get('commentsFrom') || $request->get('commentsTo')) {
                $queryHelper = $this->createQueryBuilder('b')
                    ->leftJoin('b.comments', 'c')
                    ->addSelect('c')
                    ->select('b.id')
                    ->where('b.public = :public')
                    ->setParameter('public', true)
                    ->groupBy('b');

                if ($request->get('commentsFrom')) {
                    $queryHelper->having('COUNT(c) >= :commentsFrom')
                        ->setParameter('commentsFrom', $request->get('commentsFrom'));
                }

                if ($request->get('commentsTo')) {
                    $queryHelper->andHaving('COUNT(c) <= :commentsTo')
                        ->setParameter('commentsTo', $request->get('commentsTo'));
                }

                $ids = $queryHelper->getQuery()->getResult();

                $queryBuilder->andWhere('b.id IN (:ids)')
                    ->setParameter('ids', array_column($ids, 'id'));
            }

            if ($request->get('category')) {
                $queryBuilder->andWhere('b.category = :cat')
                    ->setParameter('cat', $request->get('category'));
            }

            if ($request->get('tags')) {
                foreach ($request->get('tags') as $tagId) {
                    $tag = $this->getEntityManager()->getRepository(Tag::class)->find($tagId);

                    if ($tag) {
                        $queryBuilder->andWhere(':tag MEMBER OF b.tags')
                            ->setParameter('tag', $tag);
                    }
                }
            }
        }

        $queryBuilder->orderBy($orderBy['value'], $orderBy['dir']);

        if (isset($orderBy['join'])) {
            $queryBuilder->groupBy('b');
        }

        return $queryBuilder->getQuery();
    }
}
