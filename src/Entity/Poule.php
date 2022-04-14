<?php

namespace App\Entity;

use App\Repository\PouleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PouleRepository::class)
 * @ORM\Table(name="usb_poules")
 */
class Poule
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $Name;

    /**
     * @ORM\ManyToOne(targetEntity=Phase::class, inversedBy="poules")
     */
    private $Phase;

    /**
     * @ORM\ManyToMany(targetEntity=Team::class, inversedBy="poules")
     * @ORM\JoinTable(name="usb_poules_teams")
     */
    private $Teams;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $principal;

    public function __construct()
    {
        $this->Teams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getPhase(): ?Phase
    {
        return $this->Phase;
    }

    public function setPhase(?Phase $Phase): self
    {
        $this->Phase = $Phase;

        return $this;
    }

    /**
     * @return Collection<int, Team>
     */
    public function getTeams(): Collection
    {
        return $this->Teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->Teams->contains($team)) {
            $this->Teams[] = $team;
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        $this->Teams->removeElement($team);

        return $this;
    }

    public function getPrincipal(): ?bool
    {
        return $this->principal;
    }

    public function setPrincipal(?bool $principal): self
    {
        $this->principal = $principal;

        return $this;
    }
}
