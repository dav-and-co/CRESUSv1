<?php

namespace App\Entity;

use App\Repository\RevenuRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RevenuRepository::class)]
class Revenu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $montant_mensuel = null;

    #[ORM\ManyToOne(inversedBy: 'revenus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeRevenu $type_revenu = null;

    #[ORM\ManyToOne(inversedBy: 'revenus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Beneficiaire $beneficiaire = null;

    #[ORM\ManyToOne(inversedBy: 'revenus')]
    private ?Demande $demande = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaires = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontantMensuel(): ?int
    {
        return $this->montant_mensuel;
    }

    public function setMontantMensuel(int $montant_mensuel): static
    {
        $this->montant_mensuel = $montant_mensuel;

        return $this;
    }

    public function getTypeRevenu(): ?TypeRevenu
    {
        return $this->type_revenu;
    }

    public function setTypeRevenu(?TypeRevenu $type_revenu): static
    {
        $this->type_revenu = $type_revenu;

        return $this;
    }

    public function getBeneficiaire(): ?Beneficiaire
    {
        return $this->beneficiaire;
    }

    public function setBeneficiaire(?Beneficiaire $beneficiaire): static
    {
        $this->beneficiaire = $beneficiaire;

        return $this;
    }

    public function getDemande(): ?Demande
    {
        return $this->demande;
    }

    public function setDemande(?Demande $demande): static
    {
        $this->demande = $demande;

        return $this;
    }

    public function getCommentaires(): ?string
    {
        return $this->commentaires;
    }

    public function setCommentaires(?string $commentaires): static
    {
        $this->commentaires = $commentaires;

        return $this;
    }
}
