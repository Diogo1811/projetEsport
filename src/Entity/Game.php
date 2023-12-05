<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\GameRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Ce jeu a déjà été ajouté à la base de données')]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(groups: 'id')]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    #[Groups(groups: 'name')]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Editor $editor = null;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: SocialMediaAccount::class)]
    private Collection $socialMediaAccounts;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Roster::class, orphanRemoval: true)]
    private Collection $rosters;

    #[ORM\Column]
    private ?bool $isVerified = null;

    public function __construct()
    {
        $this->socialMediaAccounts = new ArrayCollection();
        $this->rosters = new ArrayCollection();
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

    public function isIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    //adding a  __toString function
    public function __toString()
    {
        return ucfirst($this->getName());
    }
 
}
