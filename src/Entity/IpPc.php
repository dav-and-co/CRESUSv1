<?php

namespace App\Entity;

use App\Repository\IpPcRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IpPcRepository::class)]
class IpPc
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $identifiant_PC = null;

    /**
     * @var Collection<int, Connexion>
     */
    #[ORM\OneToMany(targetEntity: Connexion::class, mappedBy: 'idPC')]
    private Collection $connexions;

    public function __construct()
    {
        $this->connexions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentifiantPC(): ?string
    {
        return $this->identifiant_PC;
    }

    public function setIdentifiantPC(string $identifiant_PC): static
    {
        $this->identifiant_PC = $identifiant_PC;

        return $this;
    }

    /**
     * @return Collection<int, Connexion>
     */
    public function getConnexions(): Collection
    {
        return $this->connexions;
    }

    public function addConnexion(Connexion $connexion): static
    {
        if (!$this->connexions->contains($connexion)) {
            $this->connexions->add($connexion);
            $connexion->setIdPC($this);
        }

        return $this;
    }

    public function removeConnexion(Connexion $connexion): static
    {
        if ($this->connexions->removeElement($connexion)) {
            // set the owning side to null (unless already changed)
            if ($connexion->getIdPC() === $this) {
                $connexion->setIdPC(null);
            }
        }

        return $this;
    }
}
