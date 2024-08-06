<?php

namespace App\Entity;

use App\Repository\TypeRevenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRevenuRepository::class)]
class TypeRevenu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle_revenu = null;

    /**
     * @var Collection<int, Revenu>
     */
    #[ORM\OneToMany(targetEntity: Revenu::class, mappedBy: 'type_renevu')]
    private Collection $revenus;

    public function __construct()
    {
        $this->revenus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleRevenu(): ?string
    {
        return $this->libelle_revenu;
    }

    public function setLibelleRevenu(string $libelle_revenu): static
    {
        $this->libelle_revenu = $libelle_revenu;

        return $this;
    }

    /**
     * @return Collection<int, Revenu>
     */
    public function getRevenus(): Collection
    {
        return $this->revenus;
    }

    public function addRevenu(Revenu $revenu): static
    {
        if (!$this->revenus->contains($revenu)) {
            $this->revenus->add($revenu);
            $revenu->setTypeRevenu($this);
        }

        return $this;
    }

    public function removeRevenu(Revenu $revenu): static
    {
        if ($this->revenus->removeElement($revenu)) {
            // set the owning side to null (unless already changed)
            if ($revenu->getTypeRevenu() === $this) {
                $revenu->setTypeRevenu(null);
            }
        }

        return $this;
    }
}
