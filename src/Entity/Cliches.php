<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ClichesRepository")
 * @ORM\Table(indexes={
 *  @ORM\Index(name="search_description", columns={"description"}),
 *  @ORM\Index(name="search_date", columns={"date_de_prise"})
 * })
 */
class Cliches
{

    /**
     * @Groups({"villes","tailles","sujets","series","indexpers","indexicos","cindoc", "cliches"})
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"tailles","sujets","series","indexpers","indexicos","cindoc", "cliches"})
     * Des clichés ont plusieurs villes
     * @ORM\ManyToMany(targetEntity="App\Entity\Villes", mappedBy="cliches", cascade={"persist"})
     */
    private $villes;

    /**
     * @Groups({"villes","tailles","sujets","series","indexpers","indexicos","cindoc", "cliches"})
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @Groups({"villes","tailles","sujets","series","indexpers","indexicos","cindoc", "cliches"})
     * 
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_de_prise;

    /**
     * @Groups({"villes","tailles","sujets","series","indexpers","indexicos","cindoc", "cliches"})
     * 
     * @ORM\Column(type="text", nullable=true)
     */
    private $fichier;

    /**
     * @Groups({"villes","tailles","sujets","series","indexpers","indexicos","cindoc", "cliches"})
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $support;

    /**
     * @Groups({"villes","tailles","sujets","series","indexpers","indexicos","cindoc", "cliches"})
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $chroma;

    /**
     * @Groups({"villes","tailles","sujets","series","indexpers","indexicos","cindoc", "cliches"})
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $discriminant;

    /**
     * @Groups({"villes","tailles","sujets","series","indexpers","indexicos","cindoc", "cliches"})
     * 
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nb_Cliche;

    /**
     * @Groups({"villes","tailles","sujets","series","indexpers","indexicos","cindoc", "cliches"})
     * 
     * @ORM\Column(type="text", nullable=true)
     */
    private $note_de_bas_De_Page;

    /**
     * @Groups({"villes","sujets","series","indexpers","indexicos","cindoc", "cliches"})
     * Un cliché a de 0 à 1 taille
     * @ORM\ManyToOne(targetEntity="App\Entity\Tailles", inversedBy="cliches",cascade={"persist"})
     * @ORM\JoinColumn(name="tailles", referencedColumnName="id")
     */
    private $taille;

    /**
     * @Groups({"villes","tailles","series","indexpers","indexicos","cindoc", "cliches"})
     * Un cliché a de 0 à N sujets
     * @ORM\ManyToMany(targetEntity="App\Entity\Sujets", mappedBy="cliches",cascade={"persist"})
     */
    private $sujets;

    /**
     * @Groups({"villes","tailles","sujets","indexpers","indexicos","cindoc", "cliches"})
     * Un cliché à de 0 à une série
     * @ORM\ManyToOne(targetEntity="App\Entity\Series", inversedBy="cliches",cascade={"persist"})
     * @ORM\JoinColumn(name="series", referencedColumnName="id")
     */
    private $serie;

    /**
     * @Groups({"villes","tailles","sujets","series","indexicos","cindoc", "cliches"})
     * Un cliché à de 0 à N index personnes
     * @ORM\ManyToMany(targetEntity="App\Entity\IndexPersonnes", mappedBy="cliches",cascade={"persist"})
     */
    private $indexPersonnes;

    /**
     * @Groups({"villes","tailles","sujets","series","indexpers","cindoc", "cliches"})
     * Un cliché à de 0 à N index iconographiques
     * @ORM\ManyToMany(targetEntity="App\Entity\IndexIconographiques", mappedBy="cliches",cascade={"persist"})
     */
    private $indexIconographiques;

    /**
     * 
     * @ORM\Column(type="text", nullable=true)
     */
    private $remarque;

    /**
     * @Groups({"villes","tailles","sujets","series","indexpers","indexicos", "cliches"})
     * Un cliché à de 0 à N cindoc
     * @ORM\ManyToMany(targetEntity="App\Entity\Cindoc", mappedBy="cliches",cascade={"persist"})
     */
    private $cindoc;

    public function __construct()
    {
        $this->villes = new ArrayCollection();
        $this->sujets = new ArrayCollection();
        $this->indexPersonnes = new ArrayCollection();
        $this->indexIconographiques = new ArrayCollection();
        $this->cindoc = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateDePrise(): ?\DateTimeInterface
    {
        return $this->date_de_prise;
    }

    public function setDateDePrise(?\DateTimeInterface $date_de_prise): self
    {
        $this->date_de_prise = $date_de_prise;

        return $this;
    }

    public function getFichier(): ?string
    {
        return $this->fichier;
    }

    public function setFichier(?string $fichier): self
    {
        $this->fichier = $fichier;

        return $this;
    }

    public function getSupport(): ?string
    {
        return $this->support;
    }

    public function setSupport(?string $support): self
    {
        $this->support = $support;

        return $this;
    }

    public function getChroma(): ?string
    {
        return $this->chroma;
    }

    public function setChroma(?string $chroma): self
    {
        $this->chroma = $chroma;

        return $this;
    }

    public function getDiscriminant(): ?string
    {
        return $this->discriminant;
    }

    public function setDiscriminant(?string $discriminant): self
    {
        $this->discriminant = $discriminant;

        return $this;
    }

    public function getNbCliche(): ?int
    {
        return $this->nb_Cliche;
    }

    public function setNbCliche(int $nb_Cliche): self
    {
        $this->nb_Cliche = $nb_Cliche;

        return $this;
    }

    public function getNoteDeBasDePage(): ?string
    {
        return $this->note_de_bas_De_Page;
    }

    public function setNoteDeBasDePage(?string $note_de_bas_De_Page): self
    {
        $this->note_de_bas_De_Page = $note_de_bas_De_Page;

        return $this;
    }

    public function getRemarque(): ?string
    {
        return $this->remarque;
    }

    public function setRemarque(?string $remarque): self
    {
        $this->remarque = $remarque;

        return $this;
    }

    /**
     * @return Collection|Villes[]
     */
    public function getVilles(): Collection
    {
        return $this->villes;
    }

    public function addVille(Villes $ville): self
    {
        if (!$this->villes->contains($ville)) {
            $this->villes[] = $ville;
            $ville->addClich($this);
        }

        return $this;
    }

    public function removeVille(Villes $ville): self
    {
        if ($this->villes->contains($ville)) {
            $this->villes->removeElement($ville);
            $ville->removeClich($this);
        }

        return $this;
    }

    public function getTaille(): ?Tailles
    {
        return $this->taille;
    }

    public function setTaille(?Tailles $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    /**
     * @return Collection|Sujets[]
     */
    public function getSujets(): Collection
    {
        return $this->sujets;
    }

    public function addSujet(Sujets $sujet): self
    {
        if (!$this->sujets->contains($sujet)) {
            $this->sujets[] = $sujet;
            $sujet->addClich($this);
        }

        return $this;
    }

    public function removeSujet(Sujets $sujet): self
    {
        if ($this->sujets->contains($sujet)) {
            $this->sujets->removeElement($sujet);
            $sujet->removeClich($this);
        }

        return $this;
    }

    public function getSerie(): ?Series
    {
        return $this->serie;
    }

    public function setSerie(?Series $serie): self
    {
        $this->serie = $serie;

        return $this;
    }

    /**
     * @return Collection|IndexPersonnes[]
     */
    public function getIndexPersonnes(): Collection
    {
        return $this->indexPersonnes;
    }

    public function addIndexPersonne(IndexPersonnes $indexPersonne): self
    {
        if (!$this->indexPersonnes->contains($indexPersonne)) {
            $this->indexPersonnes[] = $indexPersonne;
            $indexPersonne->addClich($this);
        }

        return $this;
    }

    public function removeIndexPersonne(IndexPersonnes $indexPersonne): self
    {
        if ($this->indexPersonnes->contains($indexPersonne)) {
            $this->indexPersonnes->removeElement($indexPersonne);
            $indexPersonne->removeClich($this);
        }

        return $this;
    }

    /**
     * @return Collection|IndexIconographiques[]
     */
    public function getIndexIconographiques(): Collection
    {
        return $this->indexIconographiques;
    }

    public function addIndexIconographique(IndexIconographiques $indexIconographique): self
    {
        if (!$this->indexIconographiques->contains($indexIconographique)) {
            $this->indexIconographiques[] = $indexIconographique;
            $indexIconographique->addClich($this);
        }

        return $this;
    }

    public function removeIndexIconographique(IndexIconographiques $indexIconographique): self
    {
        if ($this->indexIconographiques->contains($indexIconographique)) {
            $this->indexIconographiques->removeElement($indexIconographique);
            $indexIconographique->removeClich($this);
        }

        return $this;
    }

    /**
     * @return Collection|Cindoc[]
     */
    public function getCindoc(): Collection
    {
        return $this->cindoc;
    }

    public function addCindoc(Cindoc $cindoc): self
    {
        if (!$this->cindoc->contains($cindoc)) {
            $this->cindoc[] = $cindoc;
            $cindoc->addClich($this);
        }

        return $this;
    }

    public function removeCindoc(Cindoc $cindoc): self
    {
        if ($this->cindoc->contains($cindoc)) {
            $this->cindoc->removeElement($cindoc);
            $cindoc->removeClich($this);
        }

        return $this;
    }


}

    