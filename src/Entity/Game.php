<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $releaseDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $linkToPurchase = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Editor $editor = null;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Roster::class, orphanRemoval: true)]
    private Collection $rosters;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Tournament::class)]
    private Collection $tournaments;

    public function __construct()
    {
        $this->rosters = new ArrayCollection();
        $this->tournaments = new ArrayCollection();
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

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): static
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getLinkToPurchase(): ?string
    {
        return $this->linkToPurchase;
    }

    public function setLinkToPurchase(?string $linkToPurchase): static
    {
        $this->linkToPurchase = $linkToPurchase;

        return $this;
    }

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
     * @return Collection<int, Roster>
     */
    public function getRosters(): Collection
    {
        return $this->rosters;
    }

    public function addRoster(Roster $roster): static
    {
        if (!$this->rosters->contains($roster)) {
            $this->rosters->add($roster);
            $roster->setGame($this);
        }

        return $this;
    }

    public function removeRoster(Roster $roster): static
    {
        if ($this->rosters->removeElement($roster)) {
            // set the owning side to null (unless already changed)
            if ($roster->getGame() === $this) {
                $roster->setGame(null);
            }
        }

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
}
