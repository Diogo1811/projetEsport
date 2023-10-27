<?php

namespace App\Entity;

use App\Repository\SocialMediaAccountRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SocialMediaAccountRepository::class)]
class SocialMediaAccount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $linkToSocialMedia = null;

    #[ORM\ManyToOne(inversedBy: 'socialMediaAccounts')]
    private ?Player $player = null;

    #[ORM\ManyToOne(inversedBy: 'socialMediaAccounts')]
    private ?Team $team = null;

    #[ORM\ManyToOne(inversedBy: 'socialMediaAccounts')]
    private ?Editor $editor = null;

    #[ORM\ManyToOne(inversedBy: 'socialMediaAccounts')]
    private ?Game $game = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLinkToSocialMedia(): ?string
    {
        return $this->linkToSocialMedia;
    }

    public function setLinkToSocialMedia(?string $linkToSocialMedia): static
    {
        $this->linkToSocialMedia = $linkToSocialMedia;

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

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): static
    {
        $this->team = $team;

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

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
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
}
