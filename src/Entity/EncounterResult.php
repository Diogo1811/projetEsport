<?php

namespace App\Entity;

use App\Repository\EncounterResultRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EncounterResultRepository::class)]
class EncounterResult
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 11, scale: 2)]
    private ?string $score = null;

    #[ORM\Column]
    private ?int $ranking = null;

    #[ORM\ManyToOne(inversedBy: 'encounterResults')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Encounter $encounter = null;

    #[ORM\ManyToOne(inversedBy: 'encounterResults')]
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

    public function getEncounter(): ?Encounter
    {
        return $this->encounter;
    }

    public function setEncounter(?Encounter $encounter): static
    {
        $this->encounter = $encounter;

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
