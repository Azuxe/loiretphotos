<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\SujetsRepository")
 */
class Sujets
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
     * @ORM\Column(type="string", length=255,unique =true,nullable = false)
     */
    private $sujet;

    /**
     * @Groups({"sujets"})
     * Un sujet a de 0 à N clichés
     * @ORM\ManyToMany(targetEntity="App\Entity\Cliches", inversedBy="sujets")
     * @ORM\JoinTable(name="sujets_cliches")
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

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): self
    {
        $this->sujet = $sujet;

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
        }

        return $this;
    }

    public function removeClich(Cliches $clich): self
    {
        if ($this->cliches->contains($clich)) {
            $this->cliches->removeElement($clich);
        }

        return $this;
    }

    
}
