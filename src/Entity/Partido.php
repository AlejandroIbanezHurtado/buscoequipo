<?php

namespace App\Entity;

use App\Entity\Equipo;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PartidoRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=PartidoRepository::class)
 */
class Partido
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Pista::class, inversedBy="partidos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pista;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_ini;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_fin;

    /**
     * @ORM\OneToMany(targetEntity=Valoracion::class, mappedBy="partido")
     */
    private $valoraciones;

    /**
     * @ORM\OneToMany(targetEntity=valoracion::class, mappedBy="partido")
     */
    private $valoracion;

    public function __construct()
    {
        $this->valoraciones = new ArrayCollection();
        $this->valoracion = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPista(): ?Pista
    {
        return $this->pista;
    }

    public function setPista(?Pista $pista): self
    {
        $this->pista = $pista;

        return $this;
    }

    public function getFechaIni(): ?\DateTimeInterface
    {
        return $this->fecha_ini;
    }

    public function setFechaIni(\DateTimeInterface $fecha_ini): self
    {
        $this->fecha_ini = $fecha_ini;

        return $this;
    }

    public function getFechaFin(): ?\DateTimeInterface
    {
        return $this->fecha_fin;
    }

    public function setFechaFin(\DateTimeInterface $fecha_fin): self
    {
        $this->fecha_fin = $fecha_fin;

        return $this;
    }

    /**
     * @return Collection<int, Valoracion>
     */
    public function getValoraciones(): Collection
    {
        return $this->valoraciones;
    }

    public function addValoracione(Valoracion $valoracione): self
    {
        if (!$this->valoraciones->contains($valoracione)) {
            $this->valoraciones[] = $valoracione;
            $valoracione->setPartido($this);
        }

        return $this;
    }

    public function removeValoracione(Valoracion $valoracione): self
    {
        if ($this->valoraciones->removeElement($valoracione)) {
            // set the owning side to null (unless already changed)
            if ($valoracione->getPartido() === $this) {
                $valoracione->setPartido(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, valoracion>
     */
    public function getValoracion(): Collection
    {
        return $this->valoracion;
    }

    public function addValoracion(valoracion $valoracion): self
    {
        if (!$this->valoracion->contains($valoracion)) {
            $this->valoracion[] = $valoracion;
            $valoracion->setPartido($this);
        }

        return $this;
    }

    public function removeValoracion(valoracion $valoracion): self
    {
        if ($this->valoracion->removeElement($valoracion)) {
            // set the owning side to null (unless already changed)
            if ($valoracion->getPartido() === $this) {
                $valoracion->setPartido(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->id." - ".$this->fecha_ini->format('d-m-Y H:i:s');
    }
}
