<?php

namespace App\Entity;

use App\Repository\PermananceSiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PermananceSiteRepository::class)]
class PermananceSite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $heureAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $heureEnd = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $benevole1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $benevole2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $benevole3 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $benevole4 = null;

    #[ORM\ManyToOne(inversedBy: 'permananceSites')]
    private ?Site $idSite = null;

    /**
     * @var Collection<int, RendezVous>
     */
    #[ORM\OneToMany(targetEntity: RendezVous::class, mappedBy: 'idSite')]
    private Collection $rendezVouses;

    public function __construct()
    {
        $this->rendezVouses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateAt(): ?\DateTimeImmutable
    {
        return $this->dateAt;
    }

    public function setDateAt(\DateTimeImmutable $dateAt): static
    {
        $this->dateAt = $dateAt;

        return $this;
    }

    public function getHeureAt(): ?\DateTimeImmutable
    {
        return $this->heureAt;
    }

    public function setHeureAt(\DateTimeImmutable $heureAt): static
    {
        $this->heureAt = $heureAt;

        return $this;
    }

    public function getHeureEnd(): ?\DateTimeInterface
    {
        return $this->heureEnd;
    }

    public function setHeureEnd(\DateTimeInterface $heureEnd): static
    {
        $this->heureEnd = $heureEnd;

        return $this;
    }

    public function getBenevole1(): ?string
    {
        return $this->benevole1;
    }

    public function setBenevole1(?string $benevole1): static
    {
        $this->benevole1 = $benevole1;

        return $this;
    }

    public function getBenevole2(): ?string
    {
        return $this->benevole2;
    }

    public function setBenevole2(?string $benevole2): static
    {
        $this->benevole2 = $benevole2;

        return $this;
    }

    public function getBenevole3(): ?string
    {
        return $this->benevole3;
    }

    public function setBenevole3(?string $benevole3): static
    {
        $this->benevole3 = $benevole3;

        return $this;
    }

    public function getBenevole4(): ?string
    {
        return $this->benevole4;
    }

    public function setBenevole4(?string $benevole4): static
    {
        $this->benevole4 = $benevole4;

        return $this;
    }

    public function getIdSite(): ?Site
    {
        return $this->idSite;
    }

    public function setIdSite(?Site $idSite): static
    {
        $this->idSite = $idSite;

        return $this;
    }

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRendezVouses(): Collection
    {
        return $this->rendezVouses;
    }

    public function addRendezVouse(RendezVous $rendezVouse): static
    {
        if (!$this->rendezVouses->contains($rendezVouse)) {
            $this->rendezVouses->add($rendezVouse);
            $rendezVouse->setIdSite($this);
        }

        return $this;
    }

    public function removeRendezVouse(RendezVous $rendezVouse): static
    {
        if ($this->rendezVouses->removeElement($rendezVouse)) {
            // set the owning side to null (unless already changed)
            if ($rendezVouse->getIdSite() === $this) {
                $rendezVouse->setIdSite(null);
            }
        }

        return $this;
    }
}
