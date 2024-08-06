<?php

namespace App\Entity;

use App\Repository\AvancementRepository;
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
}
