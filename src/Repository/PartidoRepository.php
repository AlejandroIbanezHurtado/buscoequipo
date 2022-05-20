<?php

namespace App\Repository;

use App\Entity\Partido;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Partido|null find($id, $lockMode = null, $lockVersion = null)
 * @method Partido|null findOneBy(array $criteria, array $orderBy = null)
 * @method Partido[]    findAll()
 * @method Partido[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartidoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Partido::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Partido $entity, bool $flush = true): void
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
    public function remove(Partido $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function obtenUltimoPartido()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select distinct equipo.nombre as 'nombre_equipo', fecha_ini, fecha_fin, equipo.escudo from partido inner join partido_equipo on partido.id = partido_equipo.id_partido_id inner join equipo on equipo.id = partido_equipo.id_equipo_id where fecha_ini > NOW() order by fecha_ini limit 2";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $partidos = $resultSet->fetchAll();
        return $partidos;
    }

    public function obtenUltimoPartidoJugadoTorneo()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select partido.id, torneo.nombre, tipo from partido inner join torneo_partido on partido.id = torneo_partido.id_partido_id inner join torneo on torneo.id = torneo_partido.id_torneo_id where partido.fecha_fin < NOW() order by partido.fecha_fin desc limit 1";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $id = $resultSet->fetchAll();
        return $id;
    }

    public function obtenDatosEquiposPorId($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select distinct equipo.id, escudo, equipo.nombre, partido.fecha_ini from partido inner join partido_equipo on partido.id = partido_equipo.id_partido_id inner join equipo on equipo.id = partido_equipo.id_equipo_id where partido.id=${id}";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $datos_equipos = $resultSet->fetchAll();
        return $datos_equipos;
    }

    public function obtenGolesPartidoPorId($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select equipo_id, count(id) as 'goles' from detalle_partido where partido_id = ${id} AND color is null group by equipo_id";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $datos_partido = $resultSet->fetchAll();
        return $datos_partido;
    }

    public function obtenPartidosIndex()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select partido_id, equipo_id, count(partido_id) as 'goles', nombre, escudo from detalle_partido inner join equipo on detalle_partido.equipo_id = equipo.id where color is null group by equipo_id,partido_id order by rand() limit 9";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $partidos = $resultSet->fetchAll();
        return $partidos;
    }

    


    // /**
    //  * @return Partido[] Returns an array of Partido objects
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
    public function findOneBySomeField($value): ?Partido
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
