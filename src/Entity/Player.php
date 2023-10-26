<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $lastName = null;

    #[ORM\Column(length: 100)]
    private ?string $firstname = null;

    #[ORM\Column(length: 100)]
    private ?string $nickname = null;

    #[ORM\Column(length: 10)]
    private ?string $gender = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $biography = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 11, scale: 2)]
    private ?string $earning = null;

    #[ORM\ManyToMany(targetEntity: Country::class, inversedBy: 'players')]
    private Collection $countrys;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: PlayerRoster::class)]
    private Collection $playerRosters;

    public function __construct()
    {
        $this->countrys = new ArrayCollection();
        $this->playerRosters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): static
    {
        $this->biography = $biography;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;

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

    /**
     * @return Collection<int, Country>
     */
    public function getCountrys(): Collection
    {
        return $this->countrys;
    }

    public function addCountry(Country $country): static
    {
        if (!$this->countrys->contains($country)) {
            $this->countrys->add($country);
        }

        return $this;
    }

    public function removeCountry(Country $country): static
    {
        $this->countrys->removeElement($country);

        return $this;
    }

    /**
     * @return Collection<int, PlayerRoster>
     */
    public function getPlayerRosters(): Collection
    {
        return $this->playerRosters;
    }

    public function addPlayerRoster(PlayerRoster $playerRoster): static
    {
        if (!$this->playerRosters->contains($playerRoster)) {
            $this->playerRosters->add($playerRoster);
            $playerRoster->setPlayer($this);
        }

        return $this;
    }

    public function removePlayerRoster(PlayerRoster $playerRoster): static
    {
        if ($this->playerRosters->removeElement($playerRoster)) {
            // set the owning side to null (unless already changed)
            if ($playerRoster->getPlayer() === $this) {
                $playerRoster->setPlayer(null);
            }
        }

        return $this;
    }
}
