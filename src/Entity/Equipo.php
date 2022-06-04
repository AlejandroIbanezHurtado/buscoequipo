<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EquipoRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=EquipoRepository::class)
 */
class Equipo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Length(
     *      min = 6,
     *      max = 20,
     *      minMessage = "El nombre debe de tener como mínimo {{ limit }} caracteres",
     *      maxMessage = "El nombre no debe de ser mayor a {{ limit }} caracteres"
     * )
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $nombre;

    /**
     * @ORM\Column(type="boolean")
     */
    private $permanente;

    /**
     * @ORM\ManyToOne(targetEntity=Jugador::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $capitan;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $escudo="interrogacion.png";

    /**
     * @ORM\OneToMany(targetEntity=Partido::class, mappedBy="equipo1")
     */
    private $partidos;

    public function __construct()
    {
        $this->partidos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPermanente(): ?bool
    {
        return $this->permanente;
    }

    public function setPermanente(bool $permanente): self
    {
        $this->permanente = $permanente;

        return $this;
    }

    public function getCapitan(): ?Jugador
    {
        return $this->capitan;
    }

    public function setCapitan(?Jugador $capitan): self
    {
        $this->capitan = $capitan;

        return $this;
    }

    public function getEscudo(): ?string
    {
        return $this->escudo;
    }

    public function setEscudo(?string $escudo): self
    {
        $this->escudo = $escudo;

        return $this;
    }

    /**
     * @return Collection<int, Partido>
     */
    public function getPartidos(): Collection
    {
        return $this->partidos;
    }

    public function addPartido(Partido $partido): self
    {
        if (!$this->partidos->contains($partido)) {
            $this->partidos[] = $partido;
            $partido->setEquipo1($this);
        }

        return $this;
    }

    public function removePartido(Partido $partido): self
    {
        if ($this->partidos->removeElement($partido)) {
            // set the owning side to null (unless already changed)
            if ($partido->getEquipo1() === $this) {
                $partido->setEquipo1(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nombre;
    }
}
