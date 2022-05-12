<?php

namespace App\Repository;

use App\Entity\Products;
use App\filters\ProductsFilters;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Notifier\Channel\BrowserChannel;

/**
 * @extends ServiceEntityRepository<Products>
 *
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Products $entity, bool $flush = false): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Products $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    public function findRand($maxResults){

        return $this->createQueryBuilder('p')
        ->orderBy('RAND()')
        ->setMaxResults($maxResults)
        ->getQuery()
        ->getResult()
        ;
    }

//    /**
//     * @return Products[] Returns an array of Products objects
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

//    public function findOneBySomeField($value): ?Products
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findFilters(ProductsFilters $filter)
    {
        $query =  $this->createQueryBuilder("p")
        ->leftJoin("p.brewries", "b")
        ->leftJoin("b.countries", "c")
        ->leftJoin("p.styles", "st")
        ;

        if($filter->search)
        {
            $query = $query
            ->andWhere('p.name LIKE :search')
            ->orWhere("b.name LIKE :search")
            ->orWhere('p.description LIKE :search')
            ->orWhere('c.name LIKE :search')
            ->setParameter("search", "%$filter->search%")
            ;
        }

        if($filter->minPrice)
        {
            $query = $query
            ->andwhere('p.price >= :min')
            ->setParameter("min", $filter->minPrice)
            ;
        }

        if($filter->maxPrice)
        {
            $query = $query
            ->andwhere('p.price <= :max')
            ->setParameter("max", $filter->maxPrice)
            ;
        }

        if($filter->style)
        {
            $query = $query
            ->andWhere('st.id IN (:st)')
            ->setParameter("st", $filter->style)
            ;
        }

        if($filter->country)
        {
            $query = $query
            ->andWhere('c.id IN (:country)')
            ->setParameter("country", $filter->country)
            ;
        }

        if($filter->brewry)
        {
            $query = $query
            ->andWhere('b.id IN (:br)')
            ->setParameter("br", $filter->brewry)
            ;
        }

        if($filter->order)
        {
            switch($filter->order)
            {
                case 1:
                    $query = $query
                    ->orderBy("p.price", "ASC")
                    ;
                    break;

                    case 2:
                        $query = $query
                        ->orderBy("p.price", "DESC")
                        ;
                        break;
                    
                    case 3:
                        $query = $query
                        ->orderBy("p.createdAt", "DESC")
                        ;
                        break;

                    case 4:
                        $query = $query
                        ->orderBy("p.name", "ASC")
                        ;
                        break;

                    case 5:
                        $query = $query
                        ->orderBy("p.name", "DESC")
                        ;
                        break;
                }
        }

        return $query->getQuery()->getResult();

    }
}
