<?php

namespace App\Entity;

use App\Repository\EditorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EditorRepository::class)]
class Editor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $linkToOfficialPage = null;

    #[ORM\ManyToOne(inversedBy: 'editors')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Country $country = null;

    #[ORM\OneToMany(mappedBy: 'editor', targetEntity: Game::class)]
    private Collection $games;

    #[ORM\OneToMany(mappedBy: 'editor', targetEntity: SocialMediaAccount::class)]
    private Collection $socialMediaAccounts;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null;

    public function __construct()
    {
        $this->games = new ArrayCollection();
        $this->socialMediaAccounts = new ArrayCollection();
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

    public function getLinkToOfficialPage(): ?string
    {
        return $this->linkToOfficialPage;
    }

    public function setLinkToOfficialPage(?string $linkToOfficialPage): static
    {
        $this->linkToOfficialPage = $linkToOfficialPage;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): static
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
            $game->setEditor($this);
        }

        return $this;
    }

    public function removeGame(Game $game): static
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getEditor() === $this) {
                $game->setEditor(null);
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
            $socialMediaAccount->setEditor($this);
        }

        return $this;
    }

    public function removeSocialMediaAccount(SocialMediaAccount $socialMediaAccount): static
    {
        if ($this->socialMediaAccounts->removeElement($socialMediaAccount)) {
            // set the owning side to null (unless already changed)
            if ($socialMediaAccount->getEditor() === $this) {
                $socialMediaAccount->setEditor(null);
            }
        }

        return $this;
    }

    //adding a  __tostring function
    public function __toString()
    {
        return ucfirst($this->getName());
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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }
}
