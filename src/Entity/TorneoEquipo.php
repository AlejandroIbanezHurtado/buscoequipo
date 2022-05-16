<?php

namespace App\Entity;

use App\Repository\TorneoEquipoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TorneoEquipoRepository::class)
 */
class TorneoEquipo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Torneo::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_torneo;

    /**
     * @ORM\ManyToOne(targetEntity=Equipo::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_equipo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdTorneo(): ?Torneo
    {
        return $this->id_torneo;
    }

    public function setIdTorneo(?Torneo $id_torneo): self
    {
        $this->id_torneo = $id_torneo;

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
