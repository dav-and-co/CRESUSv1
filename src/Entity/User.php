<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nomUser = null;

    #[ORM\Column(length: 255)]
    private ?string $prenomUser = null;

    #[ORM\Column(nullable: true)]
    private ?int $telPerso = null;

    #[ORM\Column(nullable: true)]
    private ?int $telAssoc = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mailPerso = null;

    #[ORM\Column]
    private ?bool $isActif = null;

    /**
     * @var Collection<int, Connexion>
     */
    #[ORM\OneToMany(targetEntity: Connexion::class, mappedBy: 'user')]
    private Collection $connexions;

    /**
     * @var Collection<int, Demande>
     */
    #[ORM\ManyToMany(targetEntity: Demande::class, inversedBy: 'users')]
    private Collection $demande;

    /**
     * @var Collection<int, Permanence>
     */
    #[ORM\ManyToMany(targetEntity: Permanence::class, inversedBy: 'users')]
    private Collection $permanences;

    /**
     * @var Collection<int, RendezVous>
     */
    #[ORM\OneToMany(targetEntity: RendezVous::class, mappedBy: 'user')]
    private Collection $rendezVouses;

    public function __construct()
    {
        $this->connexions = new ArrayCollection();
        $this->demande = new ArrayCollection();
        $this->permanences = new ArrayCollection();
        $this->rendezVouses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNomUser(): ?string
    {
        return $this->nomUser;
    }

    public function setNomUser(string $nomUser): static
    {
        $this->nomUser = mb_strtoupper($nomUser, 'UTF-8');

        return $this;
    }

    public function getPrenomUser(): ?string
    {
        return $this->prenomUser;
    }

    public function setPrenomUser(string $prenomUser): static
    {
        $this->prenomUser = mb_strtoupper($prenomUser, 'UTF-8');

        return $this;
    }

    public function getTelPerso(): ?int
    {
        return $this->telPerso;
    }

    public function setTelPerso(?int $telPerso): static
    {
        $this->telPerso = $telPerso;

        return $this;
    }

    public function getTelAssoc(): ?int
    {
        return $this->telAssoc;
    }

    public function setTelAssoc(?int $telAssoc): static
    {
        $this->telAssoc = $telAssoc;

        return $this;
    }

    public function getMailPerso(): ?string
    {
        return $this->mailPerso;
    }

    public function setMailPerso(?string $mailPerso): static
    {
        $this->mailPerso = $mailPerso;

        return $this;
    }


    public function getIsActif(): ?bool
    {
        return $this->isActif;
    }

    public function setIsActif(bool $isActif): static
    {
        $this->isActif = $isActif;

        return $this;
    }

    /**
     * @return Collection<int, Connexion>
     */
    public function getConnexions(): Collection
    {
        return $this->connexions;
    }

    public function addConnexion(Connexion $connexion): static
    {
        if (!$this->connexions->contains($connexion)) {
            $this->connexions->add($connexion);
            $connexion->setUser($this);
        }

        return $this;
    }

    public function removeConnexion(Connexion $connexion): static
    {
        if ($this->connexions->removeElement($connexion)) {
            // set the owning side to null (unless already changed)
            if ($connexion->getUser() === $this) {
                $connexion->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Demande>
     */
    public function getDemande(): Collection
    {
        return $this->demande;
    }

    public function addDemande(Demande $demande): static
    {
        if (!$this->demande->contains($demande)) {
            $this->demande->add($demande);

        }

        return $this;
    }

    public function removeDemande(Demande $demande): static
    {
        $this->demande->removeElement($demande);


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
        }

        return $this;
    }

    public function removePermanence(Permanence $permanence): static
    {
        $this->permanences->removeElement($permanence);

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
            $rendezVouse->setUser($this);
        }

        return $this;
    }

    public function removeRendezVouse(RendezVous $rendezVouse): static
    {
        if ($this->rendezVouses->removeElement($rendezVouse)) {
            // set the owning side to null (unless already changed)
            if ($rendezVouse->getUser() === $this) {
                $rendezVouse->setUser(null);
            }
        }

        return $this;
    }
}
