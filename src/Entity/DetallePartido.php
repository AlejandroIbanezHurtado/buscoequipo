<?php

namespace App\Entity;

use App\Repository\DetallePartidoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DetallePartidoRepository::class)
 */
class DetallePartido
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $color;

    /**
     * @ORM\Column(type="integer", length=2)
     */
    private $minuto;

    /**
     * @ORM\ManyToOne(targetEntity=Jugador::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $jugador;

    /**
     * @ORM\ManyToOne(targetEntity=Partido::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $partido;

    /**
     * @ORM\ManyToOne(targetEntity=Equipo::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $equipo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColor(): ?bool
    {
        return $this->color;
    }

    public function setColor(?bool $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getMinuto(): ?string
    {
        return $this->minuto;
    }

    public function setMinuto(string $minuto): self
    {
        $this->minuto = $minuto;

        return $this;
    }

    public function getJugador(): ?Jugador
    {
        return $this->jugador;
    }

    public function setJugador(?Jugador $jugador): self
    {
        $this->jugador = $jugador;

        return $this;
    }

    public function getPartido(): ?Partido
    {
        return $this->partido;
    }

    public function setPartido(?Partido $partido): self
    {
        $this->partido = $partido;

        return $this;
    }

    public function getEquipo(): ?Equipo
    {
        return $this->equipo;
    }

    public function setEquipo(?Equipo $equipo): self
    {
        $this->equipo = $equipo;

        return $this;
    }
}
