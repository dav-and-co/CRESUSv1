<?php

namespace App\Entity;

use App\Repository\OrigineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrigineRepository::class)]
class Origine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle_origine = null;

    /**
     * @var Collection<int, Demande>
     */
    #[ORM\OneToMany(targetEntity: Demande::class, mappedBy: 'origine')]
    private Collection $demandes;

    #[ORM\Column(nullable: true)]
    private ?bool $isActif = null;

    public function __construct()
    {
        $this->demandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleOrigine(): ?string
    {
        return $this->libelle_origine;
    }

    public function setLibelleOrigine(string $libelle_origine): static
    {
        $this->libelle_origine = $libelle_origine;

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
            $demande->setOrigine($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): static
    {
        if ($this->demandes->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getOrigine() === $this) {
                $demande->setOrigine(null);
            }
        }

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->isActif;
    }

    public function setActif(?bool $isActif): static
    {
        $this->isActif = $isActif;

        return $this;
    }
}
