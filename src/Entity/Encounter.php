<?php

namespace App\Entity;

use App\Repository\EncounterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EncounterRepository::class)]
class Encounter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $linkToReplay = null;

    #[ORM\OneToMany(mappedBy: 'encounter', targetEntity: EncounterResult::class)]
    private Collection $encounterResults;

    public function __construct()
    {
        $this->encounterResults = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getLinkToReplay(): ?string
    {
        return $this->linkToReplay;
    }

    public function setLinkToReplay(?string $linkToReplay): static
    {
        $this->linkToReplay = $linkToReplay;

        return $this;
    }

    /**
     * @return Collection<int, EncounterResult>
     */
    public function getEncounterResults(): Collection
    {
        return $this->encounterResults;
    }

    public function addEncounterResult(EncounterResult $encounterResult): static
    {
        if (!$this->encounterResults->contains($encounterResult)) {
            $this->encounterResults->add($encounterResult);
            $encounterResult->setEncounter($this);
        }

        return $this;
    }

    public function removeEncounterResult(EncounterResult $encounterResult): static
    {
        if ($this->encounterResults->removeElement($encounterResult)) {
            // set the owning side to null (unless already changed)
            if ($encounterResult->getEncounter() === $this) {
                $encounterResult->setEncounter(null);
            }
        }

        return $this;
    }
}
