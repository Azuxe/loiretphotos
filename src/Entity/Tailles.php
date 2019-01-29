<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaillesRepository")
 * @ORM\Table(name="Tailles",uniqueConstraints={@ORM\UniqueConstraint(name="tailles_unique", columns={"hauteur_cm", "largeur_cm"})})
 */
class Tailles
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private $hauteur_cm;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private $largeur_cm;

    /**
     * Une taille a de 0 à N clichés
     * @ORM\OneToMany(targetEntity="App\Entity\Cliches", mappedBy="taille")
     */
    private $cliches;

    public function __construct()
    {
        $this->cliches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHauteurCm(): ?float
    {
        return $this->hauteur_cm;
    }

    public function setHauteurCm(float $hauteur_cm): self
    {
        $this->hauteur_cm = $hauteur_cm;

        return $this;
    }

    public function getLargeurCm(): ?float
    {
        return $this->largeur_cm;
    }

    public function setLargeurCm(float $largeur_cm): self
    {
        $this->largeur_cm = $largeur_cm;

        return $this;
    }

    /**
     * @return Collection|Cliches[]
     */
    public function getCliches(): Collection
    {
        return $this->cliches;
    }

    public function addClich(Cliches $clich): self
    {
        if (!$this->cliches->contains($clich)) {
            $this->cliches[] = $clich;
            $clich->setTaille($this);
        }

        return $this;
    }

    public function removeClich(Cliches $clich): self
    {
        if ($this->cliches->contains($clich)) {
            $this->cliches->removeElement($clich);
            // set the owning side to null (unless already changed)
            if ($clich->getTaille() === $this) {
                $clich->setTaille(null);
            }
        }

        return $this;
    }


}