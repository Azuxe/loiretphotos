<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaillesRepository")
 * @ORM\Table(name="Tailles")
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="TAILLE_HAUTEUR_LARGEUR", columns={"hauteur_cm", "largeur_cm"})})
 */
class Tailles
{
    /**
     * @Groups({"villes","tailles","sujets","series","indexpers","indexicos","cindoc", "cliches"})
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"villes","tailles","sujets","series","indexpers","indexicos","cindoc", "cliches"})
     * @ORM\Column(type="float", nullable=true)
     */
    private $hauteur_cm;

    /**
     * @Groups({"villes","tailles","sujets","series","indexpers","indexicos","cindoc", "cliches"})
     * @ORM\Column(type="float", nullable=true)
     */
    private $largeur_cm;

    /**
     * @Groups({"tailles"})
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
