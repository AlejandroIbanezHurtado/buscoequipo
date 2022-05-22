<?php

namespace App\Entity;

use App\Repository\EquipoJugadorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquipoJugadorRepository::class)
 */
class EquipoJugador
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Equipo::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $equipo;

    /**
     * @ORM\ManyToOne(targetEntity=Jugador::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $jugador;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getJugador(): ?Jugador
    {
        return $this->jugador;
    }

    public function setJugador(?Jugador $jugador): self
    {
        $this->jugador = $jugador;

        return $this;
    }
}
