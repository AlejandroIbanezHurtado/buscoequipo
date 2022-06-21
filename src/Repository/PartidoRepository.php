<?php

namespace App\Repository;

use stdClass;
use App\Entity\Partido;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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
        $sql = "select equipo_id, count(id) as 'goles' from detalle_partido where partido_id = ${id} AND gol=1 group by equipo_id";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $datos_partido = $resultSet->fetchAll();
        return $datos_partido;
    }

    public function obtenPartidosIndex($limit = 9)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select distinct id_partido_id as partido_id, id_equipo_id as equipo_id, equipo.nombre, equipo.escudo, goles 
        from equipo inner join partido_equipo on partido_equipo.id_equipo_id=equipo.id 
        left join (select P.partido_id, P.equipo_id, P.nombre, P.escudo, count(*) as 'goles' 
        from (select detalle_partido.partido_id, detalle_partido.equipo_id, equipo.nombre, equipo.escudo, Par.fecha_ini, detalle_partido.gol 
        from detalle_partido inner join equipo on detalle_partido.equipo_id = equipo.id 
        inner join (select * from partido order by fecha_ini desc limit ${limit}) as Par on detalle_partido.partido_id = Par.id where gol=1) as P 
        group by P.partido_id, P.equipo_id order by P.partido_id) as h 
        on h.partido_id = partido_equipo.id_partido_id and h.equipo_id=partido_equipo.id_equipo_id order by partido_equipo.id_partido_id";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $partidos = $resultSet->fetchAll();
        return $partidos;
    }

    
    public function obtenEquipoEntreFecha($id_jugador, $fecha_ini, $fecha_fin, $perma)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select * from partido inner join partido_equipo on partido.id = partido_equipo.id_partido_id inner join equipo on equipo.id = partido_equipo.id_equipo_id inner join equipo_jugador on equipo.id = equipo_jugador.equipo_id WHERE ((date_add('${fecha_ini}', INTERVAL 1 MICROSECOND) BETWEEN fecha_ini AND fecha_fin) OR (date_sub('${fecha_fin}', INTERVAL 1 MICROSECOND) BETWEEN fecha_ini AND fecha_fin)) and equipo_jugador.jugador_id = ${id_jugador}";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $partido = $resultSet->fetchAll();
        return $partido;
    }

    public function obtenPartido($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select partido.*,partido_equipo.*,equipo.*,pista.nombre as nombre_pista, pista.imagen from partido inner join partido_equipo on partido.id = partido_equipo.id_partido_id inner join equipo on partido_equipo.id_equipo_id = equipo.id inner join pista on partido.pista_id = pista.id where partido.id = ${id}";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $partido = $resultSet->fetchAll();
        return $partido;
    }

    public function obtenResultados($id_partido)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select distinct id_partido_id as partido_id, id_equipo_id as equipo_id, equipo.nombre, equipo.escudo, goles, (select fecha_ini from partido where partido.id = id_partido_id) as fecha
        from equipo inner join partido_equipo on partido_equipo.id_equipo_id=equipo.id 
        left join (select P.partido_id, P.equipo_id, P.nombre, P.escudo, count(*) as 'goles' 
        from (select detalle_partido.partido_id, detalle_partido.equipo_id, equipo.nombre, equipo.escudo, Par.fecha_ini, detalle_partido.gol 
        from detalle_partido inner join equipo on detalle_partido.equipo_id = equipo.id 
        inner join (select * from partido order by fecha_ini desc) as Par on detalle_partido.partido_id = Par.id where gol=1) as P 
        group by P.partido_id, P.equipo_id order by P.partido_id) as h 
        on h.partido_id = partido_equipo.id_partido_id and h.equipo_id=partido_equipo.id_equipo_id where partido_id=${id_partido}";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $partido = $resultSet->fetchAll();
        return $partido;
    }

    public function obtenDetalle($id_partido)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select * from detalle_partido inner join jugador on detalle_partido.jugador_id = jugador.id where detalle_partido.partido_id = ${id_partido} order by minuto";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $partido = $resultSet->fetchAll();
        return $partido;
    }

    public function obtenMostrarDetalle($id_partido, $id_jugador)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select * from equipo_jugador inner join equipo on equipo_jugador.jugador_id = equipo.capitan_id inner join partido_equipo on equipo.id = partido_equipo.id_equipo_id inner join partido on partido.id = partido_equipo.id_partido_id where partido_equipo.id_partido_id=${id_partido} and equipo_jugador.jugador_id=${id_jugador} and partido.fecha_ini>NOW() and NOT EXISTS (select * from detalle_partido where detalle_partido.partido_id = ${id_partido})";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $partido = $resultSet->fetchAll();
        return $partido;
    }

    public function obtenJugadoresPorPartido($id_partido)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select * from jugador inner join equipo_jugador on jugador.id = equipo_jugador.jugador_id inner join partido_equipo on equipo_jugador.equipo_id = partido_equipo.id_equipo_id where partido_equipo.id_partido_id = ${id_partido}";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $partido = $resultSet->fetchAll();
        return $partido;
    }

    public function obtenPartidosPaginados(int $pagina=1, int $filas=5, int $perma=1, string $order="asc")
    {
        $obj = new stdClass();
        $filas = $filas*2;
        $conn = $this->getEntityManager()->getConnection();

        $registros = array();
        $sql = "select distinct id_partido_id as partido_id, id_equipo_id as equipo_id, equipo.nombre, equipo.escudo, goles, (select fecha_ini from partido where partido.id = id_partido_id) as fecha
        from equipo inner join partido_equipo on partido_equipo.id_equipo_id=equipo.id 
        left join (select P.partido_id, P.equipo_id, P.nombre, P.escudo, count(*) as 'goles' 
        from (select detalle_partido.partido_id, detalle_partido.equipo_id, equipo.nombre, equipo.escudo, Par.fecha_ini, detalle_partido.gol 
        from detalle_partido inner join equipo on detalle_partido.equipo_id = equipo.id 
        inner join (select * from partido order by fecha_ini desc) as Par on detalle_partido.partido_id = Par.id where gol=1) as P 
        group by P.partido_id, P.equipo_id order by P.partido_id) as h 
        on h.partido_id = partido_equipo.id_partido_id and h.equipo_id=partido_equipo.id_equipo_id where equipo.permanente=${perma} order by fecha ${order}";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $registros = $resultSet->fetchAll();
        $obj->n_total = $n_total = count($registros);

        $total = count($registros);
        $paginas = ceil($total /$filas);
        $registros = array();
        if ($pagina <= $paginas)
        {
            $inicio = ($pagina-1) * $filas;
            $sql = "select distinct id_partido_id as partido_id, id_equipo_id as equipo_id, equipo.nombre, equipo.escudo, goles, (select fecha_ini from partido where partido.id = id_partido_id) as fecha
            from equipo inner join partido_equipo on partido_equipo.id_equipo_id=equipo.id 
            left join (select P.partido_id, P.equipo_id, P.nombre, P.escudo, count(*) as 'goles' 
            from (select detalle_partido.partido_id, detalle_partido.equipo_id, equipo.nombre, equipo.escudo, Par.fecha_ini, detalle_partido.gol 
            from detalle_partido inner join equipo on detalle_partido.equipo_id = equipo.id 
            inner join (select * from partido order by fecha_ini desc) as Par on detalle_partido.partido_id = Par.id where gol=1) as P 
            group by P.partido_id, P.equipo_id order by P.partido_id) as h 
            on h.partido_id = partido_equipo.id_partido_id and h.equipo_id=partido_equipo.id_equipo_id where equipo.permanente=${perma} order by fecha ${order} limit $inicio, $filas";
            $stmt = $conn->prepare($sql);
            $resultSet = $stmt->executeQuery();
            $registros = $resultSet->fetchAll(); 
        }
        $obj->registros=$registros;
        return $obj;
    }

    public function obtenMisPartidos($id_jugador)
    {
        $obj = new stdClass();
        $conn = $this->getEntityManager()->getConnection();

        $registros = array();
        $sql = "select distinct id_partido_id as partido_id, id_equipo_id as equipo_id, equipo.nombre, equipo.escudo, goles, (select fecha_ini from partido where partido.id = id_partido_id) as fecha from equipo inner join partido_equipo on partido_equipo.id_equipo_id=equipo.id inner join equipo_jugador on equipo_jugador.equipo_id = equipo.id left join (select P.partido_id, P.equipo_id, P.nombre, P.escudo, count(*) as 'goles' from (select detalle_partido.partido_id, detalle_partido.equipo_id, equipo.nombre, equipo.escudo, Par.fecha_ini, detalle_partido.gol from detalle_partido inner join equipo on detalle_partido.equipo_id = equipo.id inner join (select * from partido order by fecha_ini desc) as Par on detalle_partido.partido_id = Par.id where gol=1) as P group by P.partido_id, P.equipo_id order by P.partido_id) as h on h.partido_id = partido_equipo.id_partido_id and h.equipo_id=partido_equipo.id_equipo_id where equipo_jugador.jugador_id = ${id_jugador} order by partido_equipo.id_partido_id";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $registros = $resultSet->fetchAll();
        $obj->registros=$registros;
        return $obj;
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
