<?php

namespace App\Repository;

use App\Entity\Picture;
use App\Entity\Property;
use App\Entity\PropertySearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

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

    private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Property::class);
        $this->paginator = $paginator;
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
     * @return PaginationInterface Returns a page of unsold properties (with params in search filter)
     */
    public function paginateAllVisible(PropertySearch $search, int $page): PaginationInterface
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

        // if location defined, search within a distance of 10km max by default
        if ($search->getLatitude() && $search->getLongitude()) {
            $query = $query
                ->andWhere('6371 * 2 * ASIN(SQRT(POWER(SIN((p.latitude - :lat) * pi()/180 / 2), 2) + COS(p.latitude * pi()/180) * COS(:lat * pi()/180) * POWER(SIN((p.longitude - :long) * pi()/180 / 2), 2) )) <= :distance')
                ->setParameter('lat', $search->getLatitude())
                ->setParameter('long', $search->getLongitude())
                ->setParameter('distance', 10);
        }

        // if location AND distance max defined
        if ($search->getLatitude() && $search->getLongitude() && $search->getDistance()) {
            $query = $query
                ->andWhere('6371 * 2 * ASIN(SQRT(POWER(SIN((p.latitude - :lat) * pi()/180 / 2), 2) + COS(p.latitude * pi()/180) * COS(:lat * pi()/180) * POWER(SIN((p.longitude - :long) * pi()/180 / 2), 2) )) <= :distance')
                ->setParameter('lat', $search->getLatitude())
                ->setParameter('long', $search->getLongitude())
                ->setParameter('distance', $search->getDistance());
        }

        // paginator
        $properties = $this->paginator->paginate(
            $query->getQuery(), /* query NOT result */
            $page, /*page number*/
            9 /*limit per page*/
        );

        $this->hydratePicture($properties);
        return $properties;
    }

    /**
     * @return Property[] Returns an array of last 3 unsold properties
     */
    public function findLatest(): array
    {
        $properties = $this->findVisibleQuery()
            ->orderBy('p.created_at', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
        $this->hydratePicture($properties);
        return $properties;
    }

    // get picture for all properties in param
    public function hydratePicture($properties)
    {
        /*
        * if properties has getItems() method (because type Pagination has getItems() method)
        * in home page, properties = array.
        * in property page, properties = pagination
        */
        // if ($properties && method_exists($properties, 'getItems')) {
        // $properties = $properties->getItems();
        // }
        // method_exists() doesn't work if properties type is array, i used the following code : 
        if ($properties && $properties instanceof SlidingPagination) {
            $properties = $properties->getItems();
        }

        $pictures = $this->getEntityManager()->getRepository(Picture::class)->findForProperties($properties);

        foreach ($properties as $property) {
            // check if the property has an image
            if ($pictures->containsKey($property->getId())) {
                $property->setPicture($pictures->get($property->getId()));
            }
        }
    }

    /**
     * @return QueryBuilder Returns a query of all unsold properties.
     * This method is private and only used for other methods.
     */
    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            /* ->select('p', 'pics', 'options')
            ->leftJoin('p.pictures', 'pics')
            ->leftJoin('p.options', 'options')
            */
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
