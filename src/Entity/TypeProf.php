<?php

namespace App\Entity;

use App\Repository\TypeProfRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeProfRepository::class)]
class TypeProf
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle_prof = null;

    /**
     * @var Collection<int, Beneficiaire>
     */
    #[ORM\OneToMany(targetEntity: Beneficiaire::class, mappedBy: 'libelle_prof')]
    private Collection $beneficiaires;

    #[ORM\Column(nullable: true)]
    private ?bool $isActif = null;

    public function __construct()
    {
        $this->beneficiaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleProf(): ?string
    {
        return $this->libelle_prof;
    }

    public function setLibelleProf(string $libelle_prof): static
    {
        $this->libelle_prof = $libelle_prof;

        return $this;
    }

    /**
     * @return Collection<int, Beneficiaire>
     */
    public function getBeneficiaires(): Collection
    {
        return $this->beneficiaires;
    }

    public function addBeneficiaire(Beneficiaire $beneficiaire): static
    {
        if (!$this->beneficiaires->contains($beneficiaire)) {
            $this->beneficiaires->add($beneficiaire);
            $beneficiaire->setLibelleProf($this);
        }

        return $this;
    }

    public function removeBeneficiaire(Beneficiaire $beneficiaire): static
    {
        if ($this->beneficiaires->removeElement($beneficiaire)) {
            // set the owning side to null (unless already changed)
            if ($beneficiaire->getLibelleProf() === $this) {
                $beneficiaire->setLibelleProf(null);
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
