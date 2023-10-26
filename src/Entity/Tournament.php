<?php

namespace App\Entity;

use App\Repository\TournamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TournamentRepository::class)]
class Tournament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 11, scale: 2)]
    private ?string $CashPrize = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\ManyToOne(inversedBy: 'tournaments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\OneToMany(mappedBy: 'tournament', targetEntity: TournamentResult::class)]
    private Collection $tournamentResults;

    #[ORM\OneToMany(mappedBy: 'tournament', targetEntity: Encounter::class)]
    private Collection $encounters;

    public function __construct()
    {
        $this->tournamentResults = new ArrayCollection();
        $this->encounters = new ArrayCollection();
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

    public function getCashPrize(): ?string
    {
        return $this->CashPrize;
    }

    public function setCashPrize(string $CashPrize): static
    {
        $this->CashPrize = $CashPrize;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    /**
     * @return Collection<int, TournamentResult>
     */
    public function getTournamentResults(): Collection
    {
        return $this->tournamentResults;
    }

    public function addTournamentResult(TournamentResult $tournamentResult): static
    {
        if (!$this->tournamentResults->contains($tournamentResult)) {
            $this->tournamentResults->add($tournamentResult);
            $tournamentResult->setTournament($this);
        }

        return $this;
    }

    public function removeTournamentResult(TournamentResult $tournamentResult): static
    {
        if ($this->tournamentResults->removeElement($tournamentResult)) {
            // set the owning side to null (unless already changed)
            if ($tournamentResult->getTournament() === $this) {
                $tournamentResult->setTournament(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Encounter>
     */
    public function getEncounters(): Collection
    {
        return $this->encounters;
    }

    public function addEncounter(Encounter $encounter): static
    {
        if (!$this->encounters->contains($encounter)) {
            $this->encounters->add($encounter);
            $encounter->setTournament($this);
        }

        return $this;
    }

    public function removeEncounter(Encounter $encounter): static
    {
        if ($this->encounters->removeElement($encounter)) {
            // set the owning side to null (unless already changed)
            if ($encounter->getTournament() === $this) {
                $encounter->setTournament(null);
            }
        }

        return $this;
    }
}
