<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\GameRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Ce jeu a déjà été ajouté à la base de données')]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $name = null;

    // #[ORM\Column(type: Types::DATE_MUTABLE)]
    // private ?\DateTimeInterface $releaseDate = null;

    // #[ORM\Column(type: Types::TEXT, nullable: true)]
    // private ?string $linkToPurchase = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Editor $editor = null;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Tournament::class)]
    private Collection $tournaments;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: SocialMediaAccount::class)]
    private Collection $socialMediaAccounts;

    public function __construct()
    {
        $this->tournaments = new ArrayCollection();
        $this->socialMediaAccounts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    // public function getReleaseDate(): ?\DateTimeInterface
    // {
    //     return $this->releaseDate;
    // }

    // public function setReleaseDate(\DateTimeInterface $releaseDate): static
    // {
    //     $this->releaseDate = $releaseDate;

    //     return $this;
    // }

    // public function getLinkToPurchase(): ?string
    // {
    //     return $this->linkToPurchase;
    // }

    // public function setLinkToPurchase(?string $linkToPurchase): static
    // {
    //     $this->linkToPurchase = $linkToPurchase;

    //     return $this;
    // }

    public function getEditor(): ?Editor
    {
        return $this->editor;
    }

    public function setEditor(?Editor $editor): static
    {
        $this->editor = $editor;

        return $this;
    }

    /**
     * @return Collection<int, Tournament>
     */
    public function getTournaments(): Collection
    {
        return $this->tournaments;
    }

    public function addTournament(Tournament $tournament): static
    {
        if (!$this->tournaments->contains($tournament)) {
            $this->tournaments->add($tournament);
            $tournament->setGame($this);
        }

        return $this;
    }

    public function removeTournament(Tournament $tournament): static
    {
        if ($this->tournaments->removeElement($tournament)) {
            // set the owning side to null (unless already changed)
            if ($tournament->getGame() === $this) {
                $tournament->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SocialMediaAccount>
     */
    public function getSocialMediaAccounts(): Collection
    {
        return $this->socialMediaAccounts;
    }

    public function addSocialMediaAccount(SocialMediaAccount $socialMediaAccount): static
    {
        if (!$this->socialMediaAccounts->contains($socialMediaAccount)) {
            $this->socialMediaAccounts->add($socialMediaAccount);
            $socialMediaAccount->setGame($this);
        }

        return $this;
    }

    public function removeSocialMediaAccount(SocialMediaAccount $socialMediaAccount): static
    {
        if ($this->socialMediaAccounts->removeElement($socialMediaAccount)) {
            // set the owning side to null (unless already changed)
            if ($socialMediaAccount->getGame() === $this) {
                $socialMediaAccount->setGame(null);
            }
        }

        return $this;
    }

    //adding a  __tostring function
    public function __toString()
    {
        return ucfirst($this->getName());
    }
}
