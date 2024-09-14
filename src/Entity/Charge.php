<?php

namespace App\Entity;

use App\Repository\ChargeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChargeRepository::class)]
class Charge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $montant_mensuel = null;

    #[ORM\ManyToOne(inversedBy: 'charges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeCharge $type_charge = null;

    #[ORM\ManyToOne(inversedBy: 'charges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Beneficiaire $beneficiaire = null;

    #[ORM\ManyToOne(inversedBy: 'charges')]
    private ?Demande $demande = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaires = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isBDF = null;

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

    public function getTypeCharge(): ?TypeCharge
    {
        return $this->type_charge;
    }

    public function setTypeCharge(?TypeCharge $type_charge): static
    {
        $this->type_charge = $type_charge;

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

    public function isBDF(): ?bool
    {
        return $this->isBDF;
    }

    public function setBDF(?bool $isBDF): static
    {
        $this->isBDF = $isBDF;

        return $this;
    }
}
