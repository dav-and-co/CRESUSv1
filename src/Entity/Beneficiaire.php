<?php

namespace App\Entity;

use App\Repository\BeneficiaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BeneficiaireRepository::class)]
class Beneficiaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $civilite_beneficiaire = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_beneficiaire = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom_beneficiaire = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $ddn_beneficiaire = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mail_beneficiaire = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $telephone_beneficiaire = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profession_beneficiaire = null;

    #[ORM\ManyToOne(inversedBy: 'beneficiaires')]
    private ?TypeProf $libelle_prof = null;

    /**
     * @var Collection<int, Revenu>
     */
    #[ORM\OneToMany(targetEntity: Revenu::class, mappedBy: 'beneficiaire')]
    private Collection $revenus;

    /**
     * @var Collection<int, Charge>
     */
    #[ORM\OneToMany(targetEntity: Charge::class, mappedBy: 'beneficiaire')]
    private Collection $charges;

    /**
     * @var Collection<int, Dette>
     */
    #[ORM\OneToMany(targetEntity: Dette::class, mappedBy: 'titulaire_principal')]
    private Collection $dettes;

    public function __construct()
    {
        $this->revenus = new ArrayCollection();
        $this->charges = new ArrayCollection();
        $this->dettes = new ArrayCollection();
       }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCiviliteBeneficiaire(): ?string
    {
        return $this->civilite_beneficiaire;
    }

    public function setCiviliteBeneficiaire(string $civilite_beneficiaire): static
    {
        $this->civilite_beneficiaire = $civilite_beneficiaire;

        return $this;
    }

    public function getNomBeneficiaire(): ?string
    {
        return $this->nom_beneficiaire;
    }

    public function setNomBeneficiaire(string $nom_beneficiaire): static
    {
        $this->nom_beneficiaire = $nom_beneficiaire;

        return $this;
    }

    public function getPrenomBeneficiaire(): ?string
    {
        return $this->prenom_beneficiaire;
    }

    public function setPrenomBeneficiaire(string $prenom_beneficiaire): static
    {
        $this->prenom_beneficiaire = $prenom_beneficiaire;

        return $this;
    }

    public function getDdnBeneficiaire(): ?\DateTimeInterface
    {
        return $this->ddn_beneficiaire;
    }

    public function setDdnBeneficiaire(?\DateTimeInterface $ddn_beneficiaire): static
    {
        $this->ddn_beneficiaire = $ddn_beneficiaire;

        return $this;
    }

    public function getMailBeneficiaire(): ?string
    {
        return $this->mail_beneficiaire;
    }

    public function setMailBeneficiaire(?string $mail_beneficiaire): static
    {
        $this->mail_beneficiaire = $mail_beneficiaire;

        return $this;
    }

    public function getTelephoneBeneficiaire(): ?int
    {
        return $this->telephone_beneficiaire;
    }

    public function setTelephoneBeneficiaire(?int $telephone_beneficiaire): static
    {
        $this->telephone_beneficiaire = $telephone_beneficiaire;

        return $this;
    }

    public function getProfessionBeneficiaire(): ?string
    {
        return $this->profession_beneficiaire;
    }

    public function setProfessionBeneficiaire(?string $profession_beneficiaire): static
    {
        $this->profession_beneficiaire = $profession_beneficiaire;

        return $this;
    }

    public function getLibelleProf(): ?TypeProf
    {
        return $this->libelle_prof;
    }

    public function setLibelleProf(?TypeProf $libelle_prof): static
    {
        $this->libelle_prof = $libelle_prof;

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
            $revenu->setBeneficiaire($this);
        }

        return $this;
    }

    public function removeRevenu(Revenu $revenu): static
    {
        if ($this->revenus->removeElement($revenu)) {
            // set the owning side to null (unless already changed)
            if ($revenu->getBeneficiaire() === $this) {
                $revenu->setBeneficiaire(null);
            }
        }

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
            $charge->setBeneficiaire($this);
        }

        return $this;
    }

    public function removeCharge(Charge $charge): static
    {
        if ($this->charges->removeElement($charge)) {
            // set the owning side to null (unless already changed)
            if ($charge->getBeneficiaire() === $this) {
                $charge->setBeneficiaire(null);
            }
        }

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
            $dette->setTitulairePrincipal($this);
        }

        return $this;
    }

    public function removeDette(Dette $dette): static
    {
        if ($this->dettes->removeElement($dette)) {
            // set the owning side to null (unless already changed)
            if ($dette->getTitulairePrincipal() === $this) {
                $dette->setTitulairePrincipal(null);
            }
        }

        return $this;
    }

}
