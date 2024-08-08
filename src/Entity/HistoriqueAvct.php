<?php

namespace App\Entity;

use App\Repository\HistoriqueAvctRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoriqueAvctRepository::class)]
class HistoriqueAvct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentairesAvct = null;

    #[ORM\ManyToOne(inversedBy: 'historiqueAvcts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Demande $demande = null;

    #[ORM\ManyToOne(inversedBy: 'historiqueAvcts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Avancement $avancement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCommentairesAvct(): ?string
    {
        return $this->commentairesAvct;
    }

    public function setCommentairesAvct(?string $commentairesAvct): static
    {
        $this->commentairesAvct = $commentairesAvct;

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

    public function getAvancement(): ?Avancement
    {
        return $this->avancement;
    }

    public function setAvancement(?Avancement $avancement): static
    {
        $this->avancement = $avancement;

        return $this;
    }
}
