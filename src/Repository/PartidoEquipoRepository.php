<?php

namespace App\Repository;

use App\Entity\PartidoEquipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PartidoEquipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method PartidoEquipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method PartidoEquipo[]    findAll()
 * @method PartidoEquipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartidoEquipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PartidoEquipo::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(PartidoEquipo $entity, bool $flush = true): void
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
    public function remove(PartidoEquipo $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function mirarSiCapiEstaEnPartido($email, $id_partido)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select * from partido_equipo inner join equipo on partido_equipo.id_equipo_id = equipo.id inner join jugador on equipo.capitan_id = jugador.id where partido_equipo.id_partido_id = ${id_partido} and jugador.email = '${email}'";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $equipos = $resultSet->fetchAll();
        return $equipos;
    }



    // /**
    //  * @return PartidoEquipo[] Returns an array of PartidoEquipo objects
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
    public function findOneBySomeField($value): ?PartidoEquipo
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
