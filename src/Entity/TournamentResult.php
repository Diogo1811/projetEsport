<?php

namespace App\Entity;

use App\Repository\TournamentResultRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TournamentResultRepository::class)]
class TournamentResult
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 11, scale: 2)]
    private ?string $score = null;

    #[ORM\Column]
    private ?int $ranking = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 11, scale: 2)]
    private ?string $earning = null;

    #[ORM\ManyToOne(inversedBy: 'tournamentResults')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tournament $tournament = null;

    #[ORM\ManyToOne(inversedBy: 'tournamentResults')]
    private ?Roster $roster = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function setScore(string $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getRanking(): ?int
    {
        return $this->ranking;
    }

    public function setRanking(int $ranking): static
    {
        $this->ranking = $ranking;

        return $this;
    }

    public function getEarning(): ?string
    {
        return $this->earning;
    }

    public function setEarning(string $earning): static
    {
        $this->earning = $earning;

        return $this;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): static
    {
        $this->tournament = $tournament;

        return $this;
    }

    public function getRoster(): ?Roster
    {
        return $this->roster;
    }

    public function setRoster(?Roster $roster): static
    {
        $this->roster = $roster;

        return $this;
    }
}
