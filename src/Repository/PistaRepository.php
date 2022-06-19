<?php

namespace App\Repository;

use App\Entity\Pista;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pista|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pista|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pista[]    findAll()
 * @method Pista[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PistaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pista::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Pista $entity, bool $flush = true): void
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
    public function remove(Pista $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function obtenPistasIndex($limit = 3, $order="rand()")
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select * from pista order by $order limit ${limit}";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $pistas = $resultSet->fetchAll();
        return $pistas;
    }

    // /**
    //  * @return Pista[] Returns an array of Pista objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Pista
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
