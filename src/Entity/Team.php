<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TeamRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Cette équipe déjà été ajouté à la base de données')]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(groups: 'id')]
    private ?int $id = null;

    #[ORM\Column(length: 2)]
    private ?string $country = null;

    #[Groups(groups: 'name')]
    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 15)]
    private ?string $zipCode = null;

    #[ORM\Column(length: 100)]
    private ?string $city = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $linkToOfficialPage = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $linkToShop = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 11, scale: 2, nullable: true)]
    private ?string $earning = null;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: Roster::class, orphanRemoval: true)]
    private Collection $rosters;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: SocialMediaAccount::class)]
    private Collection $socialMediaAccounts;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: User::class)]
    private Collection $users;

    public function __construct()
    {
        $this->rosters = new ArrayCollection();
        $this->socialMediaAccounts = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): static
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLinkToOfficialPage(): ?string
    {
        return $this->linkToOfficialPage;
    }

    public function setLinkToOfficialPage(?string $linkToOfficialPage): static
    {
        $this->linkToOfficialPage = $linkToOfficialPage;

        return $this;
    }

    public function getLinkToShop(): ?string
    {
        return $this->linkToShop;
    }

    public function setLinkToShop(?string $linkToShop): static
    {
        $this->linkToShop = $linkToShop;

        return $this;
    }

    public function getEarning(): ?string
    {
        return $this->earning;
    }

    public function setEarning(?string $earning): static
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
            $roster->setTeam($this);
        }

        return $this;
    }

    public function removeRoster(Roster $roster): static
    {
        if ($this->rosters->removeElement($roster)) {
            // set the owning side to null (unless already changed)
            if ($roster->getTeam() === $this) {
                $roster->setTeam(null);
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
            $socialMediaAccount->setTeam($this);
        }

        return $this;
    }

    public function removeSocialMediaAccount(SocialMediaAccount $socialMediaAccount): static
    {
        if ($this->socialMediaAccounts->removeElement($socialMediaAccount)) {
            // set the owning side to null (unless already changed)
            if ($socialMediaAccount->getTeam() === $this) {
                $socialMediaAccount->setTeam(null);
            }
        }

        return $this;
    }

    //adding a  __tostring function
    public function __toString()
    {
        return ucfirst($this->getName());
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setTeam($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getTeam() === $this) {
                $user->setTeam(null);
            }
        }

        return $this;
    }
}
