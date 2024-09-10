<?php

namespace App\Entity;

use App\Repository\TypeDetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeDetteRepository::class)]
class TypeDette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle_dette = null;

    /**
     * @var Collection<int, Dette>
     */
    #[ORM\OneToMany(targetEntity: Dette::class, mappedBy: 'type_dette')]
    private Collection $dettes;

    #[ORM\Column(nullable: true)]
    private ?bool $isActif = null;

    public function __construct()
    {
        $this->dettes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleDette(): ?string
    {
        return $this->libelle_dette;
    }

    public function setLibelleDette(string $libelle_dette): static
    {
        $this->libelle_dette = $libelle_dette;

        return $this;
    }

    /**
     * @return Collection<int, Dette>
     */
    public function getDettes(): Collection
    {
        return $this->dettes;
    }

    public function addDette(Dette $dette): static
    {
        if (!$this->dettes->contains($dette)) {
            $this->dettes->add($dette);
            $dette->setTypeDette($this);
        }

        return $this;
    }

    public function removeDette(Dette $dette): static
    {
        if ($this->dettes->removeElement($dette)) {
            // set the owning side to null (unless already changed)
            if ($dette->getTypeDette() === $this) {
                $dette->setTypeDette(null);
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
