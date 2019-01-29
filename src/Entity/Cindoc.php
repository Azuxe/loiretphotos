<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CindocRepository")
 */
class Cindoc
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $cindoc;

    /**
     * Un cindoc a de 0 à N cliches
     * @ORM\ManyToMany(targetEntity="App\Entity\Cliches", inversedBy="cindocs")
     * @ORM\JoinTable(name="cindocs_cliches")
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

    public function getCindoc(): ?string
    {
        return $this->cindoc;
    }

    public function setCindoc(string $cindoc): self
    {
        $this->cindoc = $cindoc;

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
