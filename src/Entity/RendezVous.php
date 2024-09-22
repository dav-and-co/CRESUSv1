<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column]
    private ?\DateTimeImmutable $heureAt = null;

    #[ORM\ManyToOne(inversedBy: 'RendezVous')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Demande $demande = null;

    #[ORM\ManyToOne(inversedBy: 'rendezVouses')]
    private ?PermananceSite $idSite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $heure_end = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaires = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDemande(): ?Demande
    {
        return $this->demande;
    }

    public function setDemande(?Demande $demande): static
    {
        $this->demande = $demande;

        return $this;
    }

    public function getIdSite(): ?PermananceSite
    {
        return $this->idSite;
    }

    public function setIdSite(?PermananceSite $idSite): static
    {
        $this->idSite = $idSite;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getHeureEnd(): ?\DateTimeInterface
    {
        return $this->heure_end;
    }

    public function setHeureEnd(?\DateTimeInterface $heure_end): static
    {
        $this->heure_end = $heure_end;

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
