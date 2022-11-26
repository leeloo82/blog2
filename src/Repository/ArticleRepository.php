<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function add(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    /**
    //     * @return Article[] Returns an array of Article objects
    //     */
    public function findOneByLike($value): array
   {
        return $this->createQueryBuilder('article')
            ->andWhere('article.description LIKE :val')
            ->setParameter('val', "%{$value}%")
            ->getQuery()
            ->getResult()
        ;
   }
    /**
    //     * @return Article[] Returns an array of Article objects
    //     */
    public function findDistinct(): array
    {
            $connexion = $this->getEntityManager()->getConnection();
            $sql = 'SELECT DISTINCT(YEAR(`date_creation`)) AS annee FROM Article a
                ORDER BY annee ASC';
            $stmt = $connexion->prepare($sql);
            $resultSet = $stmt->executeQuery();
            // returns an array of arrays (i.e. a raw data set)
            return $resultSet->fetchAllAssociative();
    }

    /**
    //     * @return Article[] Returns an array of Article objects
    //     */
    public function findValue($annee): array
    {
        $connexion = $this->getEntityManager()->getConnection();
        $sql = 'SELECT titre FROM Article a WHERE '.$annee.' = (YEAR(`date_creation`))';
        $stmt = $connexion->prepare($sql);
        $resultSet = $stmt->executeQuery();
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    /**
    //     * @return Article[] Returns an array of Article objects
    //     */
    public function findByTitle($titre): array
    {
        /*$connexion = $this->getEntityManager()->getConnection();
         $sql = 'SELECT * FROM Article a WHERE a.titre ="'.$titre.'" ';
         $stmt = $connexion->prepare($sql);
         $resultSet = $stmt->executeQuery();

         return $resultSet->fetchAllAssociative();*/
        return $this->createQueryBuilder('a')
            ->Where('a.titre =:val')
            ->setParameter('val', "$titre")
            ->getQuery()
            ->getResult()
            ;
    }

    public function findOneByIdJoinedToCategory(int $articleId): ?Article
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a, c
            FROM App\Entity\Article a
            INNER JOIN a.category c
            WHERE a.id = :id'
        )->setParameter('id', $articleId);

        return $query->getOneOrNullResult();
    }

}