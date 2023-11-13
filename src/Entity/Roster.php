<?php

namespace App\Entity;

use App\Repository\RosterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RosterRepository::class)]
class Roster
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\ManyToOne(inversedBy: 'rosters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $team = null;

    #[ORM\OneToMany(mappedBy: 'roster', targetEntity: TournamentResult::class)]
    private Collection $tournamentResults;

    #[ORM\OneToMany(mappedBy: 'roster', targetEntity: EncounterResult::class)]
    private Collection $encounterResults;

    #[ORM\OneToMany(mappedBy: 'roster', cascade: ['persist', 'remove'], targetEntity: PlayerRoster::class)]
    private Collection $playerRosters;

    public function __construct()
    {
        $this->tournamentResults = new ArrayCollection();
        $this->encounterResults = new ArrayCollection();
        $this->playerRosters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function setEndDate(?\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): static
    {
        $this->team = $team;

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
            $tournamentResult->setRoster($this);
        }

        return $this;
    }

    public function removeTournamentResult(TournamentResult $tournamentResult): static
    {
        if ($this->tournamentResults->removeElement($tournamentResult)) {
            // set the owning side to null (unless already changed)
            if ($tournamentResult->getRoster() === $this) {
                $tournamentResult->setRoster(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EncounterResult>
     */
    public function getEncounterResults(): Collection
    {
        return $this->encounterResults;
    }

    public function addEncounterResult(EncounterResult $encounterResult): static
    {
        if (!$this->encounterResults->contains($encounterResult)) {
            $this->encounterResults->add($encounterResult);
            $encounterResult->setRoster($this);
        }

        return $this;
    }

    public function removeEncounterResult(EncounterResult $encounterResult): static
    {
        if ($this->encounterResults->removeElement($encounterResult)) {
            // set the owning side to null (unless already changed)
            if ($encounterResult->getRoster() === $this) {
                $encounterResult->setRoster(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PlayerRosters>
     */
    public function getPlayerRosters(): Collection
    {
        return $this->playerRosters;
    }

    public function addPlayerRoster(PlayerRoster $playerRosters): static
    {
        if (!$this->playerRosters->contains($playerRosters)) {
            $this->playerRosters->add($playerRosters);
            $playerRosters->setRoster($this);
        }

        return $this;
    }

    public function removePlayerRoster(PlayerRoster $playerRosters): static
    {
        if ($this->playerRosters->removeElement($playerRosters)) {
            // set the owning side to null (unless already changed)
            if ($playerRosters->getRoster() === $this) {
                $playerRosters->setRoster(null);
            }
        }

        return $this;
    }
}
