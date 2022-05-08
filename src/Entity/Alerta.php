<?php

namespace App\Entity;

use App\Repository\AlertaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AlertaRepository::class)
 */
class Alerta
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=equipo::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $equipo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mensaje;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMensaje(): ?string
    {
        return $this->mensaje;
    }

    public function setMensaje(?string $mensaje): self
    {
        $this->mensaje = $mensaje;

        return $this;
    }
}
