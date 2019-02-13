<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IndexIconographiquesRepository")
 */
class IndexIconographiques
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
     * @ORM\Column(type="text", nullable=false,unique = true)
     */
    private $indexIco;

    /**
     * @Groups({"indexicos"})
     * Un index iconographique a de 0 à N cliches
     * @ORM\ManyToMany(targetEntity="App\Entity\Cliches", inversedBy="indexIconographiques")
     * @ORM\JoinTable(name="indexIconographiques_cliches")
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

    public function getIndexIco(): ?string
    {
        return $this->indexIco;
    }

    public function setIndexIco(string $indexIco): self
    {
        $this->indexIco = $indexIco;

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
