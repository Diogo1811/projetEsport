<?php

namespace App\Entity;

use App\Repository\PlayerRosterRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRosterRepository::class)]
class PlayerRoster
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $playingStartDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $playingEndDate = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $role = null;

    #[ORM\ManyToOne(inversedBy: 'playerRosters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Player $player = null;

    #[ORM\ManyToOne(inversedBy: 'playerRosters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Roster $roster = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayingStartDate(): ?\DateTimeInterface
    {
        return $this->playingStartDate;
    }

    public function setPlayingStartDate(\DateTimeInterface $playingStartDate): static
    {
        $this->playingStartDate = $playingStartDate;

        return $this;
    }

    public function getPlayingEndDate(): ?\DateTimeInterface
    {
        return $this->playingEndDate;
    }

    public function setPlayingEndDate(?\DateTimeInterface $playingEndDate): static
    {
        $this->playingEndDate = $playingEndDate;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): static
    {
        $this->player = $player;

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
