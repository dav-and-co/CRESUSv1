<?php

namespace App\Entity;

use App\Repository\IpPcRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IpPcRepository::class)]
class IpPc
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $identifiant_PC = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentifiantPC(): ?string
    {
        return $this->identifiant_PC;
    }

    public function setIdentifiantPC(string $identifiant_PC): static
    {
        $this->identifiant_PC = $identifiant_PC;

        return $this;
    }
}
