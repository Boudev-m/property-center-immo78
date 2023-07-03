<?php

namespace App\Repository;

use App\Entity\Property;
use App\Entity\PropertySearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Property>
 *
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    public function save(Property $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Property $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Property[] Returns an array of unsold properties (with search filter)
     */
    public function findAllVisible(PropertySearch $search): Query
    {
        $query = $this->findVisibleQuery();

        // if min surface entered
        if ($search->getMinSurface()) {
            $query = $query
                ->andWhere('p.surface >= :minSurface')  // andWhere for combinate min surface and max price queries
                ->setParameter('minSurface', $search->getMinSurface());
        }

        // if max price entered
        if ($search->getMaxPrice()) {
            $query = $query
                ->andWhere('p.price <= :maxPrice')  // andWhere for combinate min surface and max price queries
                ->setParameter('maxPrice', $search->getMaxPrice());
        }

        // if options selected
        // Problem to solve : getting property even if selected one or more options
        if ($search->getOptions()->count() > 0) {
            $k = 0;
            foreach ($search->getOptions() as $option) {
                $k++;
                $query = $query
                    ->andWhere(":option$k MEMBER OF p.options")  // andWhere is used for combinate queries
                    ->setParameter("option$k", $option);
            }
        }

        return $query->getQuery();
    }

    /**
     * @return Property[] Returns an array of last 3 unsold properties
     */
    public function findLatest(): array
    {
        return $this->findVisibleQuery()
            ->orderBy('p.created_at', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return QueryBuilder Returns a query of all unsold properties.
     * This method is private and only used for other methods.
     */
    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.sold = false');
    }


    //    /**
    //     * @return Property[] Returns an array of Property objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')  // permet de creer une requete avec un alias 'p'
    //            ->andWhere('p.exampleField = :val') // condition 
    //            ->setParameter('val', $value)  // parametre de requete
    //            ->orderBy('p.id', 'ASC')  // trie
    //            ->setMaxResults(10) // nbre de rslt voulu
    //            ->getQuery()  // recupere la requete
    //            ->getResult()  // recupere le rslt
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Property
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val') 
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
