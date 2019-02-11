<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\VillesRepository")
 * @UniqueEntity("nom")
 */
class Villes
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"villes", "cliches"})
     * @ORM\Column(type="string", length=255, nullable=false,unique=true)
     */
    private $nom;

    /**
     * @Groups({"villes", "cliches"})
     * @ORM\Column(type="float", nullable=true)
     */
    private $lat;

    /**
     * @Groups({"villes", "cliches"})
     * @ORM\Column(type="float", nullable=true)
     */
    private $longi;

    /**
     * @Groups({"villes"})
     * Une ville a de 0 à N clichés
     * @ORM\ManyToMany(targetEntity="App\Entity\Cliches", inversedBy="villes")
     * @ORM\JoinTable(name="villes_cliches")
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLongi(): ?float
    {
        return $this->longi;
    }

    public function setLongi(?float $longi): self
    {
        $this->longi = $longi;

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
