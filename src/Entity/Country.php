<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Ce pays a déjà été crée')]
#[UniqueEntity(fields: ['flag'], message: 'Ce drapeau à déjà été utilisé !')]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $flag = null;

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: Editor::class)]
    private Collection $editors;

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: Team::class)]
    private Collection $teams;

    #[ORM\ManyToMany(targetEntity: Player::class, mappedBy: 'countries')]
    private Collection $players;

    #[ORM\Column(length: 150)]
    private ?string $NationalityNameMale = null;

    #[ORM\Column(length: 150)]
    private ?string $NationalityNameFemale = null;

    public function __construct()
    {
        $this->editors = new ArrayCollection();
        $this->teams = new ArrayCollection();
        $this->players = new ArrayCollection();
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

    public function getFlag(): ?string
    {
        return $this->flag;
    }

    public function setFlag(string $flag): static
    {
        $this->flag = $flag;

        return $this;
    }

    /**
     * @return Collection<int, Editor>
     */
    public function getEditors(): Collection
    {
        return $this->editors;
    }

    public function addEditor(Editor $editor): static
    {
        if (!$this->editors->contains($editor)) {
            $this->editors->add($editor);
            $editor->setCountry($this);
        }

        return $this;
    }

    public function removeEditor(Editor $editor): static
    {
        if ($this->editors->removeElement($editor)) {
            // set the owning side to null (unless already changed)
            if ($editor->getCountry() === $this) {
                $editor->setCountry(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Team>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): static
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
            $team->setCountry($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): static
    {
        if ($this->teams->removeElement($team)) {
            // set the owning side to null (unless already changed)
            if ($team->getCountry() === $this) {
                $team->setCountry(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Player>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): static
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
            $player->addCountry($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): static
    {
        if ($this->players->removeElement($player)) {
            $player->removeCountry($this);
        }

        return $this;
    }

    public function getNationalityNameMale(): ?string
    {
        return $this->NationalityNameMale;
    }

    public function setNationalityNameMale(string $NationalityNameMale): static
    {
        $this->NationalityNameMale = $NationalityNameMale;

        return $this;
    }

    public function getNationalityNameFemale(): ?string
    {
        return $this->NationalityNameFemale;
    }

    public function setNationalityNameFemale(string $NationalityNameFemale): static
    {
        $this->NationalityNameFemale = $NationalityNameFemale;

        return $this;
    }

    //adding a  __tostring function
    public function __toString()
    {
        return ucwords($this->name);
    }
}
