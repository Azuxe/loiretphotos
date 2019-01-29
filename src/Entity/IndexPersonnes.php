<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IndexPersonnesRepository")
 */
class IndexPersonnes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Un index personnes a de 0 à N cliches
     * @ORM\ManyToMany(targetEntity="App\Entity\Cliches", inversedBy="indexPersonnes")
     * @ORM\JoinTable(name="indexPersonnes_cliches")
     */
    
    private $cliches;

    /**
     * @ORM\Column(type="text", nullable=false,unique = true)
     */
    private $indexPersonne;

    public function __construct()
    {
        $this->cliches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIndexPersonne(): ?string
    {
        return $this->indexPersonne;
    }

    public function setIndexPersonne(string $indexPersonne): self
    {
        $this->indexPersonne = $indexPersonne;

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
