<?php

namespace App\Entity;

use App\Repository\TorneoPartidoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TorneoPartidoRepository::class)
 */
class TorneoPartido
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
     * @ORM\ManyToOne(targetEntity=Partido::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_partido;

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

    public function getIdPartido(): ?Partido
    {
        return $this->id_partido;
    }

    public function setIdPartido(?Partido $id_partido): self
    {
        $this->id_partido = $id_partido;

        return $this;
    }
}
