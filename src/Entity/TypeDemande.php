<?php

namespace App\Entity;

use App\Repository\TypeDemandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeDemandeRepository::class)]
class TypeDemande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $libelle_demande = null;

    /**
     * @var Collection<int, Avancement>
     */
    #[ORM\OneToMany(targetEntity: Avancement::class, mappedBy: 'type_demande')]
    private Collection $avancements;

    /**
     * @var Collection<int, Demande>
     */
    #[ORM\OneToMany(targetEntity: Demande::class, mappedBy: 'type_demande')]
    private Collection $demandes;

    #[ORM\Column]
    private ?bool $isInterne = null;

    #[ORM\Column(length: 255)]
    private ?string $libelleExterne = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imgPetit = null;

    #[ORM\Column(length: 255)]
    private ?string $imgGrand = null;

    #[ORM\Column(length: 255)]
    private ?string $chemin = null;

    public function __construct()
    {
        $this->avancements = new ArrayCollection();
        $this->demandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleDemande(): ?string
    {
        return $this->libelle_demande;
    }

    public function setLibelleDemande(string $libelle_demande): static
    {
        $this->libelle_demande = $libelle_demande;

        return $this;
    }

    /**
     * @return Collection<int, Avancement>
     */
    public function getAvancements(): Collection
    {
        return $this->avancements;
    }

    public function addAvancement(Avancement $avancement): static
    {
        if (!$this->avancements->contains($avancement)) {
            $this->avancements->add($avancement);
            $avancement->setTypeDemande($this);
        }

        return $this;
    }

    public function removeAvancement(Avancement $avancement): static
    {
        if ($this->avancements->removeElement($avancement)) {
            // set the owning side to null (unless already changed)
            if ($avancement->getTypeDemande() === $this) {
                $avancement->setTypeDemande(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Demande>
     */
    public function getDemandes(): Collection
    {
        return $this->demandes;
    }

    public function addDemande(Demande $demande): static
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes->add($demande);
            $demande->setTypeDemande($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): static
    {
        if ($this->demandes->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getTypeDemande() === $this) {
                $demande->setTypeDemande(null);
            }
        }

        return $this;
    }

    public function isInterne(): ?bool
    {
        return $this->isInterne;
    }

    public function setInterne(bool $isInterne): static
    {
        $this->isInterne = $isInterne;

        return $this;
    }

    public function getLibelleExterne(): ?string
    {
        return $this->libelleExterne;
    }

    public function setLibelleExterne(string $libelleExterne): static
    {
        $this->libelleExterne = $libelleExterne;

        return $this;
    }

    public function getImgPtetit(): ?string
    {
        return $this->imgPtetit;
    }

    public function setImgPtetit(?string $imgPtetit): static
    {
        $this->imgPtetit = $imgPtetit;

        return $this;
    }

    public function getImgGrand(): ?string
    {
        return $this->imgGrand;
    }

    public function setImgGrand(string $imgGrand): static
    {
        $this->imgGrand = $imgGrand;

        return $this;
    }

    public function getChemin(): ?string
    {
        return $this->chemin;
    }

    public function setChemin(string $chemin): static
    {
        $this->chemin = $chemin;

        return $this;
    }
}
