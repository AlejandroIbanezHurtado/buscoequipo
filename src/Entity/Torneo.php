<?php

namespace App\Entity;

use App\Entity\Equipo;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TorneoRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

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
     * @ORM\ManyToOne(targetEntity=Equipo::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $equipo_creador;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $nombre;

    public function getId(): ?int
    {
        return $this->id;
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

    public function __toString()
    {
        return $this->nombre;
    }
}
