<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use App\Repository\PartidoEquipoRepository;

/**
 * @ORM\Entity(repositoryClass=PartidoEquipoRepository::class)
 */
class PartidoEquipo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Partido::class)
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $id_partido;

    /**
     * @ORM\ManyToOne(targetEntity=Equipo::class)
     * @ORM\JoinColumn(nullable=false,onDelete="CASCADE")
     */
    private $id_equipo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdPartido(): ?Partido
    {
        return $this->id_partido;
    }

    public function setIdPartido(?Partido $id_partido): self
    {
        $this->id_partido = $id_partido;

        return $this;
    }

    public function getIdEquipo(): ?Equipo
    {
        return $this->id_equipo;
    }

    public function setIdEquipo(?Equipo $id_equipo): self
    {
        $this->id_equipo = $id_equipo;

        return $this;
    }
}
