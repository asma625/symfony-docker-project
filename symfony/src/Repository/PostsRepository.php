<?php

namespace App\Repository;

use App\Entity\Posts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use function PHPUnit\Framework\returnArgument;

/**
 * @extends ServiceEntityRepository<Posts>
 */
class PostsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Posts::class);
    }

    public function save(Posts $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findRecentPosts(int $limit = 5): array 
    {
        $result = $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
        return $result;
    }
    public function findByCategoriySlug(string $slug): array
    {
        $result = $this->createQueryBuilder('p')
            ->join('p.categories', 'c')
            ->where('c.slug = :slug')
            ->setParameter('slug', $slug)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
        return $result;
    }
    public function findAllOrderByDate() : array 
    {
        $result = $this->createQueryBuilder('p')      
        ->orderBy('p.createdAt' , 'DESC')
        ->getQuery()
        ->getResult();
        return $result; 
    }
    public function finByAuthor(int $userId) : array
    {
        $result = $this->createQueryBuilder('p')
        ->where('p.user = :userId')
        ->setParameter('userId', $userId)
        ->orderBy('p.createdAt', 'DESC')
        ->getQuery()
        ->getResult();
        return $result;
    }
    public function findByCategoryAndKeyword(int $categId, int $keywordId) : array{
        $result  = $this->createQueryBuilder('p')
        ->join('p.categories', 'c')
        ->join('p.keyword', 'k')
        ->where('k.keyword =  :keywordId')
        ->andWhere('c.id =: categId')
        ->setParameter('keywordId' , $keywordId)
        ->setParameter('categId' ,$categId)
        ->getQuery()
        ->getResult();
        return $result;

    }
    public function findByFiltrs(int $cat, int $key, int $user) :array {
        $qb = $this->createQueryBuilder('p')
        ->orderBy('p.createdAt',  'DESC');

        if($cat) {
        $qb->join('p.categories', 'c')
            ->where('c.id = :catId')
            ->setParameter('catId', $cat);
        }
        if($key) {
        $qb->join('p.keywords', 'k')
            ->where('k.id = :keyId')
            ->setParameter('keyId', $key);
        }
        if($user) {
        $qb->join('p.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $user);
        }
        $qb->getQuery()
            ->getResult();
            return $qb->getQuery()->getResult();
 
    }
    public function searchByText(string $term) : array
    {
        $result = $this->createQueryBuilder('p')
        ->where('p.title like : test')
        ->setParameter('text', '%' . $term . '%')
        ->getQuery()
        ->getResult();
        return $result;
    }

    public function searchPosts(string $term, int $categoryId): array {
        $result = $this->createQueryBuilder('p')
        ->join('p.categories', 'c')
        ->where('c.id = :catid')
        ->where('p.title like : term')
        ->setParameter('catid', $categoryId)
        ->setParameter('text', '%' . $term . '%')
        ->getQuery()
        ->getResult();
        return $result;


    }


//    /**
//     * @return Posts[] Returns an array of Posts objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Posts
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
