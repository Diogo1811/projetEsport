<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
// #[UniqueEntity(fields: ['name'], message: 'Ce pays a déjà été crée')]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 2)]
    private ?string $country = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(length: 100)]
    private ?string $nickname = null;

    #[ORM\Column(length: 10)]
    private ?string $gender = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $biography = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 11, scale: 2, nullable: true)]
    private ?string $earning = null;


    #[ORM\OneToMany(mappedBy: 'player', targetEntity: PlayerRoster::class)]
    private Collection $playerRosters;

    #[ORM\OneToMany(mappedBy: 'player', cascade: ["persist"], targetEntity: SocialMediaAccount::class)]
    private Collection $socialMediaAccounts;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $picture = null;

    public function __construct()
    {
        $this->playerRosters = new ArrayCollection();
        $this->socialMediaAccounts = new ArrayCollection();
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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry($country): static
    {
        $this->country = $country;

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
            $socialMediaAccount->setPlayer($this);
        }

        return $this;
    }

    public function removeSocialMediaAccount(SocialMediaAccount $socialMediaAccount): static
    {
        if ($this->socialMediaAccounts->removeElement($socialMediaAccount)) {
            // set the owning side to null (unless already changed)
            if ($socialMediaAccount->getPlayer() === $this) {
                $socialMediaAccount->setPlayer(null);
            }
        }

        return $this;
    }

    //adding a  __tostring function
    public function __toString()
    {
        return ucfirst($this->getNickname());
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }
}
