<?php

namespace App\Entity;

use App\Repository\FormulaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormulaireRepository::class)]
class Formulaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(['message' => 'le nom ne doit pas être nul'])]
    private ?string $nom_demandeur = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(['message' => 'le prénom ne doit pas être nul'])]
    private ?string $prenom_demandeur = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Email(message: 'L\'adresse e-mail n\'est pas valide.')]
    private ?string $mail_demandeur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone_demandeur = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(['message' => 'ne doit pas être nul'])]
    private ?string $permanence_demandeur = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(['message' => 'Merci de préciser votre besoin'])]
    private ?string $besoin_demandeur = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description_besoin = null;

    #[ORM\Column]
    private ?bool $is_traite = null;

    #[ORM\Column]
    #[Assert\IsTrue(['message' => 'Vous devez accepter les conditions GDPR.'])]
    private ?bool $isGdpr = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomDemandeur(): ?string
    {
        return $this->nom_demandeur;
    }

    public function setNomDemandeur(string $nom_demandeur): static
    {
        $this->nom_demandeur = $nom_demandeur;

        return $this;
    }

    public function getPrenomDemandeur(): ?string
    {
        return $this->prenom_demandeur;
    }

    public function setPrenomDemandeur(string $prenom_demandeur): static
    {
        $this->prenom_demandeur = $prenom_demandeur;

        return $this;
    }

    public function getMailDemandeur(): ?string
    {
        return $this->mail_demandeur;
    }

    public function setMailDemandeur(string $mail_demandeur): static
    {
        $this->mail_demandeur = $mail_demandeur;

        return $this;
    }

    public function getTelephoneDemandeur(): ?string
    {
        return $this->telephone_demandeur;
    }

    public function setTelephoneDemandeur(?string $telephone_demandeur): static
    {
        $this->telephone_demandeur = $telephone_demandeur;

        return $this;
    }

    public function getPermanenceDemandeur(): ?string
    {
        return $this->permanence_demandeur;
    }

    public function setPermanenceDemandeur(string $permanence_demandeur): static
    {
        $this->permanence_demandeur = $permanence_demandeur;

        return $this;
    }

    public function getBesoinDemandeur(): ?string
    {
        return $this->besoin_demandeur;
    }

    public function setBesoinDemandeur(string $besoin_demandeur): static
    {
        $this->besoin_demandeur = $besoin_demandeur;

        return $this;
    }

    public function getDescriptionBesoin(): ?string
    {
        return $this->description_besoin;
    }

    public function setDescriptionBesoin(?string $description_besoin): static
    {
        $this->description_besoin = $description_besoin;

        return $this;
    }

     public function getIsTraite(): ?bool
    {
        return $this->is_traite;
    }
    public function setisTraite(bool $is_traite): static
    {
        $this->is_traite = $is_traite;

        return $this;
    }

    public function getIsGdpr(): ?bool
    {
        return $this->isGdpr;
    }
    public function setIsGdpr(bool $isGdpr): static
    {
        $this->isGdpr = $isGdpr;

        return $this;
    }
}

