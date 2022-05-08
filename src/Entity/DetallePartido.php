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
     * @ORM\Column(type="string", length=2)
     */
    private $minuto;

    /**
     * @ORM\ManyToOne(targetEntity=jugador::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $jugador;

    /**
     * @ORM\ManyToOne(targetEntity=partido::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $partido;

    /**
     * @ORM\ManyToOne(targetEntity=equipo::class)
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

    public function getJugador(): ?jugador
    {
        return $this->jugador;
    }

    public function setJugador(?jugador $jugador): self
    {
        $this->jugador = $jugador;

        return $this;
    }

    public function getPartido(): ?partido
    {
        return $this->partido;
    }

    public function setPartido(?partido $partido): self
    {
        $this->partido = $partido;

        return $this;
    }

    public function getEquipo(): ?equipo
    {
        return $this->equipo;
    }

    public function setEquipo(?equipo $equipo): self
    {
        $this->equipo = $equipo;

        return $this;
    }
}
