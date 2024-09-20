<?php

namespace App\Entity;

use App\Repository\SiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SiteRepository::class)]
class Site
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_site = null;

    #[ORM\Column(length: 255)]
    private ?string $intitule_site = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse1_site = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse2_site = null;

    #[ORM\Column()]
    private ?int $cp_site = null;

    #[ORM\Column(length: 255)]
    private ?string $ville_site = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $carte_site = null;

    #[ORM\Column]
    private ?bool $is_actif = null;

    /**
     * @var Collection<int, Permanence>
     */
    #[ORM\OneToMany(targetEntity: Permanence::class, mappedBy: 'site')]
    private Collection $permanences;

    /**
     * @var Collection<int, RendezVous>
     */
    #[ORM\OneToMany(targetEntity: RendezVous::class, mappedBy: 'Site')]
    private Collection $rendezVous;

    #[ORM\Column(length: 255)]
    private ?string $telSite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $couleurSite = null;

    public function __construct()
    {
        $this->permanences = new ArrayCollection();
        $this->rendezVous = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomSite(): ?string
    {
        return $this->nom_site;
    }

    public function setNomSite(string $nom_site): static
    {
        $this->nom_site = $nom_site;

        return $this;
    }

    public function getIntituleSite(): ?string
    {
        return $this->intitule_site;
    }

    public function setIntituleSite(string $intitule_site): static
    {
        $this->intitule_site = $intitule_site;

        return $this;
    }

    public function getAdresse1Site(): ?string
    {
        return $this->adresse1_site;
    }

    public function setAdresse1Site(?string $adresse1_site): static
    {
        $this->adresse1_site = $adresse1_site;

        return $this;
    }

    public function getAdresse2Site(): ?string
    {
        return $this->adresse2_site;
    }

    public function setAdresse2Site(?string $adresse2_site): static
    {
        $this->adresse2_site = $adresse2_site;

        return $this;
    }

    public function getCpSite(): ?string
    {
        return $this->cp_site;
    }

    public function setCpSite(string $cp_site): static
    {
        $this->cp_site = $cp_site;

        return $this;
    }

    public function getVilleSite(): ?string
    {
        return $this->ville_site;
    }

    public function setVilleSite(string $ville_site): static
    {
        $this->ville_site = $ville_site;

        return $this;
    }

    public function getCarteSite(): ?string
    {
        return $this->carte_site;
    }

    public function setCarteSite(string $carte_site): static
    {
        $this->carte_site = $carte_site;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->is_actif;
    }

    public function setActif(bool $is_actif): static
    {
        $this->is_actif = $is_actif;

        return $this;
    }

    /**
     * @return Collection<int, Permanence>
     */
    public function getPermanences(): Collection
    {
        return $this->permanences;
    }

    public function addPermanence(Permanence $permanence): static
    {
        if (!$this->permanences->contains($permanence)) {
            $this->permanences->add($permanence);
            $permanence->setSite($this);
        }

        return $this;
    }

    public function removePermanence(Permanence $permanence): static
    {
        if ($this->permanences->removeElement($permanence)) {
            // set the owning side to null (unless already changed)
            if ($permanence->getSite() === $this) {
                $permanence->setSite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRendezVous(): Collection
    {
        return $this->rendezVous;
    }

    public function addRendezVou(RendezVous $rendezVou): static
    {
        if (!$this->rendezVous->contains($rendezVou)) {
            $this->rendezVous->add($rendezVou);
            $rendezVou->setSite($this);
        }

        return $this;
    }

    public function removeRendezVou(RendezVous $rendezVou): static
    {
        if ($this->rendezVous->removeElement($rendezVou)) {
            // set the owning side to null (unless already changed)
            if ($rendezVou->getSite() === $this) {
                $rendezVou->setSite(null);
            }
        }

        return $this;
    }

    public function getTelSite(): ?string
    {
        return $this->telSite;
    }

    public function setTelSite(string $telSite): static
    {
        $this->telSite = $telSite;

        return $this;
    }

    public function getCouleurSite(): ?string
    {
        return $this->couleurSite;
    }

    public function setCouleurSite(?string $couleurSite): static
    {
        $this->couleurSite = $couleurSite;

        return $this;
    }
}
