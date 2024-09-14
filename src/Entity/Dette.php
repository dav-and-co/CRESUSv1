<?php

namespace App\Entity;

use App\Repository\DetteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetteRepository::class)]
class Dette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $organisme = null;

    #[ORM\Column(nullable: true)]
    private ?int $montant_initial = null;

    #[ORM\Column(nullable: true)]
    private ?int $mensualite = null;

    #[ORM\Column]
    private ?int $montant_du = null;

    #[ORM\ManyToOne(inversedBy: 'dettes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Beneficiaire $titulaire_principal = null;

    #[ORM\ManyToOne(inversedBy: 'dettes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeDette $type_dette = null;

    #[ORM\ManyToOne(inversedBy: 'dettes')]
    private ?Demande $demande = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaires = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrganisme(): ?string
    {
        return $this->organisme;
    }

    public function setOrganisme(string $organisme): static
    {
        $this->organisme = $organisme;

        return $this;
    }

    public function getMontantInitial(): ?int
    {
        return $this->montant_initial;
    }

    public function setMontantInitial(?int $montant_initial): static
    {
        $this->montant_initial = $montant_initial;

        return $this;
    }

    public function getMensualite(): ?int
    {
        return $this->mensualite;
    }

    public function setMensualite(?int $mensualite): static
    {
        $this->mensualite = $mensualite;

        return $this;
    }

    public function getMontantDu(): ?int
    {
        return $this->montant_du;
    }

    public function setMontantDu(int $montant_du): static
    {
        $this->montant_du = $montant_du;

        return $this;
    }

    public function getTitulairePrincipal(): ?Beneficiaire
    {
        return $this->titulaire_principal;
    }

    public function setTitulairePrincipal(?Beneficiaire $titulaire_principal): static
    {
        $this->titulaire_principal = $titulaire_principal;

        return $this;
    }

    public function getTypeDette(): ?TypeDette
    {
        return $this->type_dette;
    }

    public function setTypeDette(?TypeDette $type_dette): static
    {
        $this->type_dette = $type_dette;

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
