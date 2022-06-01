<?php

namespace App\Repository;

use App\Entity\Equipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Equipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Equipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Equipo[]    findAll()
 * @method Equipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Equipo::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Equipo $entity, bool $flush = true): void
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
    public function remove(Equipo $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function obtenEquiposIndex($limit = 9)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select equipo.nombre as 'nombre_equipo', equipo.escudo, jugador.nombre as 'nombre_jugador', jugador.apellidos, jugador.imagen from equipo inner join jugador on equipo.capitan_id = jugador.id order by rand() limit ${limit}";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $equipos = $resultSet->fetchAll();
        return $equipos;
    }

    public function obtenEquipoCompleto($perma=1,$id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select count(equipo.id) as 'numero' from equipo inner join jugador on equipo.capitan_id = jugador.id inner join equipo_jugador on equipo.id = equipo_jugador.equipo_id where permanente=${perma} and equipo.id=${id} group by equipo.id";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $equipos = $resultSet->fetchAll();
        return $equipos[0]["numero"];
    }

    public function obtenEquipo($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select * from equipo where id=${id}";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $equipos = $resultSet->fetchAll();
        return $equipos;
    }

    public function obtenJugadoresPorEquipo($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select jugador_id, jugador.email,  jugador.nombre, jugador.apellidos, jugador.imagen from (equipo_jugador left join equipo on equipo.capitan_id = equipo_jugador.jugador_id) inner join jugador on equipo_jugador.jugador_id = jugador.id where equipo_jugador.equipo_id = ${id} order by equipo.capitan_id desc";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $equipos = $resultSet->fetchAll();
        return $equipos;
    }

    public function obtenEquiposPerma($perma=1)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select * from equipo where permanente=${perma}";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $equipos = $resultSet->fetchAll();
        return $equipos;
    }

    public function obtenOtroCapitan($id_capi_actual,$id_equipo)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select jugador.id from equipo inner join equipo_jugador on equipo.id = equipo_jugador.equipo_id inner join jugador on equipo_jugador.jugador_id = jugador.id where equipo.id=${id_equipo} and jugador.id !=${id_capi_actual} order by rand() limit 1";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $equipos = $resultSet->fetchAll();
        return $equipos;
    }

    public function obtenEquiposPermaPaginados(int $pagina, int $filas, $perma=1)
    {
        $conn = $this->getEntityManager()->getConnection();

        $registros = array();
        $sql = "select equipo.id, equipo.nombre as 'nombre_equipo', equipo.escudo, jugador.nombre as 'nombre_jugador', jugador.apellidos, count(equipo.id) as 'numero' from equipo inner join jugador on equipo.capitan_id = jugador.id inner join equipo_jugador on equipo.id = equipo_jugador.equipo_id where permanente=${perma} group by equipo.id";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $registros = $resultSet->fetchAll();
        $n_total = count($registros);

        $total = count($registros);
        $paginas = ceil($total /$filas);
        $registros = array();
        if ($pagina <= $paginas)
        {
            $inicio = ($pagina-1) * $filas;
            $sql = "select equipo.id, equipo.nombre as 'nombre_equipo', equipo.escudo, jugador.nombre as 'nombre_jugador', jugador.apellidos, count(equipo.id) as 'numero' from equipo inner join jugador on equipo.capitan_id = jugador.id inner join equipo_jugador on equipo.id = equipo_jugador.equipo_id where permanente=${perma} group by equipo.id limit $inicio, $filas";
            $stmt = $conn->prepare($sql);
            $resultSet = $stmt->executeQuery();
            $registros = $resultSet->fetchAll(); 
        }
        return $registros;
    }

    // /**
    //  * @return Equipo[] Returns an array of Equipo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Equipo
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
