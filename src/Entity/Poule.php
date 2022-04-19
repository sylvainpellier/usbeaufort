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

    /**
     * @ORM\OneToMany(targetEntity=Meet::class, mappedBy="Poule")
     */
    private $meets;

    /**
     * @ORM\OneToMany(targetEntity=Position::class, mappedBy="PouleFrom", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $positionsFrom;

    /**
     * @ORM\OneToMany(targetEntity=Position::class, mappedBy="PouleTo", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $positionsTo;



    public function __construct()
    {
        $this->Teams = new ArrayCollection();
        $this->meets = new ArrayCollection();
        $this->positionsFrom = new ArrayCollection();
        $this->positionsTo = new ArrayCollection();
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

    /**
     * @return Collection<int, Meet>
     */
    public function getMeets(): Collection
    {
        return $this->meets;
    }

    public function addMeet(Meet $meet): self
    {
        if (!$this->meets->contains($meet)) {
            $this->meets[] = $meet;
            $meet->setPoule($this);
        }

        return $this;
    }

    public function removeMeet(Meet $meet): self
    {
        if ($this->meets->removeElement($meet)) {
            // set the owning side to null (unless already changed)
            if ($meet->getPoule() === $this) {
                $meet->setPoule(null);
            }
        }

        return $this;
    }

    public function __toString() : string
    {
        return $this->getName();
    }

    /**
     * @return Collection<int, Position>
     */
    public function getPositionsFrom(): Collection
    {
        return $this->positionsFrom;
    }

    public function addPositionFrom(Position $position): self
    {
        if (!$this->positionsFrom->contains($position)) {
            $this->positionsFrom[] = $position;
            $position->setPouleFrom($this);
        }

        return $this;
    }

    public function removePositionFrom(Position $position): self
    {
        if ($this->positionsFrom->removeElement($position)) {
            // set the owning side to null (unless already changed)
            if ($position->getPouleFrom() === $this) {
                $position->setPouleFrom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Position>
     */
    public function getPositionsTo(): Collection
    {
        return $this->positionsTo;
    }

    public function addPositionsTo(Position $positionsTo): self
    {
        if (!$this->positionsTo->contains($positionsTo)) {
            $this->positionsTo[] = $positionsTo;
            $positionsTo->setPouleTo($this);
        }

        return $this;
    }

    public function removePositionsTo(Position $positionsTo): self
    {
        if ($this->positionsTo->removeElement($positionsTo)) {
            // set the owning side to null (unless already changed)
            if ($positionsTo->getPouleTo() === $this) {
                $positionsTo->setPouleTo(null);
            }
        }

        return $this;
    }


}
