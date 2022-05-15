<?php

namespace App\Entity;

use App\Repository\TorneoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TorneoRepository::class)
 */
class Torneo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $equipos = [];

    /**
     * @ORM\ManyToOne(targetEntity=Equipo::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $equipo_creador;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nombre;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $partidos;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEquipos(): ?array
    {
        return $this->equipos;
    }

    public function setEquipos(?array $equipos): self
    {
        $this->equipos = $equipos;

        return $this;
    }

    public function getEquipoCreador(): ?Equipo
    {
        return $this->equipo_creador;
    }

    public function setEquipoCreador(?Equipo $equipo_creador): self
    {
        $this->equipo_creador = $equipo_creador;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getPartidos()
    {
        return $this->partidos;
    }

    public function setPartidos($partidos): self
    {
        $this->partidos = $partidos;

        return $this;
    }
}
