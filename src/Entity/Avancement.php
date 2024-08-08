<?php

namespace App\Entity;

use App\Repository\AvancementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvancementRepository::class)]
class Avancement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle_avancement = null;

    #[ORM\ManyToOne(inversedBy: 'avancements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeDemande $type_demande = null;

    /**
     * @var Collection<int, HistoriqueAvct>
     */
    #[ORM\OneToMany(targetEntity: HistoriqueAvct::class, mappedBy: 'avancement')]
    private Collection $historiqueAvcts;

    public function __construct()
    {
        $this->historiqueAvcts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleAvancement(): ?string
    {
        return $this->libelle_avancement;
    }

    public function setLibelleAvancement(string $libelle_avancement): static
    {
        $this->libelle_avancement = $libelle_avancement;

        return $this;
    }

    public function getTypeDemande(): ?TypeDemande
    {
        return $this->type_demande;
    }

    public function setTypeDemande(?TypeDemande $type_demande): static
    {
        $this->type_demande = $type_demande;

        return $this;
    }

    /**
     * @return Collection<int, HistoriqueAvct>
     */
    public function getHistoriqueAvcts(): Collection
    {
        return $this->historiqueAvcts;
    }

    public function addHistoriqueAvct(HistoriqueAvct $historiqueAvct): static
    {
        if (!$this->historiqueAvcts->contains($historiqueAvct)) {
            $this->historiqueAvcts->add($historiqueAvct);
            $historiqueAvct->setAvancement($this);
        }

        return $this;
    }

    public function removeHistoriqueAvct(HistoriqueAvct $historiqueAvct): static
    {
        if ($this->historiqueAvcts->removeElement($historiqueAvct)) {
            // set the owning side to null (unless already changed)
            if ($historiqueAvct->getAvancement() === $this) {
                $historiqueAvct->setAvancement(null);
            }
        }

        return $this;
    }
}
