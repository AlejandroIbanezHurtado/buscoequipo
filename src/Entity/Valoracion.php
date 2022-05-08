<?php

namespace App\Entity;

use App\Repository\ValoracionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ValoracionRepository::class)
 */
class Valoracion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=jugador::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $jugador;

    /**
     * @ORM\Column(type="integer")
     */
    private $puntuacion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\ManyToOne(targetEntity=Partido::class, inversedBy="valoracion")
     * @ORM\JoinColumn(nullable=false)
     */
    private $partido;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPuntuacion(): ?int
    {
        return $this->puntuacion;
    }

    public function setPuntuacion(int $puntuacion): self
    {
        $this->puntuacion = $puntuacion;

        return $this;
    }

    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    public function setComentario(?string $comentario): self
    {
        $this->comentario = $comentario;

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
}
