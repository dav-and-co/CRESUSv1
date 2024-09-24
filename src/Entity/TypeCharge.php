<?php

namespace App\Entity;

use App\Repository\TypeChargeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeChargeRepository::class)]
class TypeCharge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle_charge = null;

    /**
     * @var Collection<int, Charge>
     */
    #[ORM\OneToMany(targetEntity: Charge::class, mappedBy: 'type_charge')]
    private Collection $charges;

    #[ORM\Column(nullable: true)]
    private ?bool $isActif = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isBDF = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $color = null;

    public function __construct()
    {
        $this->charges = new ArrayCollection();
    }

        public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleCharge(): ?string
    {
        return $this->libelle_charge;
    }

    public function setLibelleCharge(string $libelle_charge): static
    {
        $this->libelle_charge = $libelle_charge;

        return $this;
    }

    /**
     * @return Collection<int, Charge>
     */
    public function getCharges(): Collection
    {
        return $this->charges;
    }

    public function addCharge(Charge $charge): static
    {
        if (!$this->charges->contains($charge)) {
            $this->charges->add($charge);
            $charge->setTypeCharge($this);
        }

        return $this;
    }

    public function removeCharge(Charge $charge): static
    {
        if ($this->charges->removeElement($charge)) {
            // set the owning side to null (unless already changed)
            if ($charge->getTypeCharge() === $this) {
                $charge->setTypeCharge(null);
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

    public function isBDF(): ?bool
    {
        return $this->isBDF;
    }

    public function setBDF(?bool $isBDF): static
    {
        $this->isBDF = $isBDF;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

}
