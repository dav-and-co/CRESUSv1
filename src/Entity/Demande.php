<?php

namespace App\Entity;

use App\Repository\DemandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandeRepository::class)]
class Demande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse1_demande = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse2_demande = null;

    #[ORM\Column(nullable: true)]
    private ?int $cp_demande = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ville_demande = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $situation_logt = null;

    #[ORM\Column(nullable: true)]
    private ?int $nb_enfant = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $patrimoine = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $complement_origine = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cause_besoin = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaires = null;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeDemande $type_demande = null;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PositionDemande $position_demande = null;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    private ?Origine $origine = null;

    /**
     * @var Collection<int, Revenu>
     */
    #[ORM\OneToMany(targetEntity: Revenu::class, mappedBy: 'demande')]
    private Collection $revenus;

    /**
     * @var Collection<int, Charge>
     */
    #[ORM\OneToMany(targetEntity: Charge::class, mappedBy: 'demande')]
    private Collection $charges;

    /**
     * @var Collection<int, Dette>
     */
    #[ORM\OneToMany(targetEntity: Dette::class, mappedBy: 'demande')]
    private Collection $dettes;

    /**
     * @var Collection<int, Beneficiaire>
     */
    #[ORM\ManyToMany(targetEntity: Beneficiaire::class, mappedBy: 'demandes')]
    private Collection $beneficiaires;

    /**
     * @var Collection<int, HistoriqueAvct>
     */
    #[ORM\OneToMany(targetEntity: HistoriqueAvct::class, mappedBy: 'demande')]
    private Collection $historiqueAvcts;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'demande')]
    private Collection $users;

    /**
     * @var Collection<int, RendezVous>
     */
    #[ORM\OneToMany(targetEntity: RendezVous::class, mappedBy: 'demande')]
    private Collection $RendezVous;

    public function __construct()
    {
        $this->revenus = new ArrayCollection();
        $this->charges = new ArrayCollection();
        $this->dettes = new ArrayCollection();
        $this->beneficiaires = new ArrayCollection();
        $this->historiqueAvcts = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->RendezVous = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getAdresse1Demande(): ?string
    {
        return $this->adresse1_demande;
    }

    public function setAdresse1Demande(?string $adresse1_demande): static
    {
        $this->adresse1_demande = $adresse1_demande;

        return $this;
    }

    public function getAdresse2Demande(): ?string
    {
        return $this->adresse2_demande;
    }

    public function setAdresse2Demande(?string $adresse2_demande): static
    {
        $this->adresse2_demande = $adresse2_demande;

        return $this;
    }

    public function getCpDemande(): ?int
    {
        return $this->cp_demande;
    }

    public function setCpDemande(?int $cp_demande): static
    {
        $this->cp_demande = $cp_demande;

        return $this;
    }

    public function getVilleDemande(): ?string
    {
        return $this->ville_demande;
    }

    public function setVilleDemande(?string $ville_demande): static
    {
        $this->ville_demande = $ville_demande;

        return $this;
    }

    public function getSituationLogt(): ?string
    {
        return $this->situation_logt;
    }

    public function setSituationLogt(?string $situation_logt): static
    {
        $this->situation_logt = $situation_logt;

        return $this;
    }

    public function getNbEnfant(): ?int
    {
        return $this->nb_enfant;
    }

    public function setNbEnfant(?int $nb_enfant): static
    {
        $this->nb_enfant = $nb_enfant;

        return $this;
    }

    public function getPatrimoine(): ?string
    {
        return $this->patrimoine;
    }

    public function setPatrimoine(?string $patrimoine): static
    {
        $this->patrimoine = $patrimoine;

        return $this;
    }

    public function getComplementOrigine(): ?string
    {
        return $this->complement_origine;
    }

    public function setComplementOrigine(?string $complement_origine): static
    {
        $this->complement_origine = $complement_origine;

        return $this;
    }

    public function getCauseBesoin(): ?string
    {
        return $this->cause_besoin;
    }

    public function setCauseBesoin(?string $cause_besoin): static
    {
        $this->cause_besoin = $cause_besoin;

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

    public function getTypeDemande(): ?TypeDemande
    {
        return $this->type_demande;
    }

    public function setTypeDemande(?TypeDemande $type_demande): static
    {
        $this->type_demande = $type_demande;

        return $this;
    }

    public function getPositionDemande(): ?PositionDemande
    {
        return $this->position_demande;
    }

    public function setPositionDemande(?PositionDemande $position_demande): static
    {
        $this->position_demande = $position_demande;

        return $this;
    }

    public function getOrigine(): ?Origine
    {
        return $this->origine;
    }

    public function setOrigine(?Origine $origine): static
    {
        $this->origine = $origine;

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
            $revenu->setDemande($this);
        }

        return $this;
    }

    public function removeRevenu(Revenu $revenu): static
    {
        if ($this->revenus->removeElement($revenu)) {
            // set the owning side to null (unless already changed)
            if ($revenu->getDemande() === $this) {
                $revenu->setDemande(null);
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
            $charge->setDemande($this);
        }

        return $this;
    }

    public function removeCharge(Charge $charge): static
    {
        if ($this->charges->removeElement($charge)) {
            // set the owning side to null (unless already changed)
            if ($charge->getDemande() === $this) {
                $charge->setDemande(null);
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
            $dette->setDemande($this);
        }

        return $this;
    }

    public function removeDette(Dette $dette): static
    {
        if ($this->dettes->removeElement($dette)) {
            // set the owning side to null (unless already changed)
            if ($dette->getDemande() === $this) {
                $dette->setDemande(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Beneficiaire>
     */
    public function getBeneficiaires(): Collection
    {
        return $this->beneficiaires;
    }

    public function addBeneficiaire(Beneficiaire $beneficiaire): static
    {
        if (!$this->beneficiaires->contains($beneficiaire)) {
            $this->beneficiaires->add($beneficiaire);
            $beneficiaire->addDemande($this);
        }

        return $this;
    }

    public function removeBeneficiaire(Beneficiaire $beneficiaire): static
    {
        if ($this->beneficiaires->removeElement($beneficiaire)) {
            $beneficiaire->removeDemande($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, HistoriqueAvct>
     */
    public function getHistoriqueAvcts(): Collection
    {
        return $this->historiqueAvcts;
    }

    public function addHistoriqueAvct(HistoriqueAvct $historiqueAvct): static
    {
        if (!$this->historiqueAvcts->contains($historiqueAvct)) {
            $this->historiqueAvcts->add($historiqueAvct);
            $historiqueAvct->setDemande($this);
        }

        return $this;
    }

    public function removeHistoriqueAvct(HistoriqueAvct $historiqueAvct): static
    {
        if ($this->historiqueAvcts->removeElement($historiqueAvct)) {
            // set the owning side to null (unless already changed)
            if ($historiqueAvct->getDemande() === $this) {
                $historiqueAvct->setDemande(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addDemande($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeDemande($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRendezVous(): Collection
    {
        return $this->RendezVous;
    }

    public function addRendezVou(RendezVous $rendezVou): static
    {
        if (!$this->RendezVous->contains($rendezVou)) {
            $this->RendezVous->add($rendezVou);
            $rendezVou->setDemande($this);
        }

        return $this;
    }

    public function removeRendezVou(RendezVous $rendezVou): static
    {
        if ($this->RendezVous->removeElement($rendezVou)) {
            // set the owning side to null (unless already changed)
            if ($rendezVou->getDemande() === $this) {
                $rendezVou->setDemande(null);
            }
        }

        return $this;
    }
}
