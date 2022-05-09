<?php

namespace App\Entity;

use App\Repository\PhaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PhaseRepository::class)
 * @ORM\Table(name="usb_phases")
 */
class Phase
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"team","matchs"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     * @Groups({"matchs"})
     */
    private $Name;

    /**
     * @ORM\OneToMany(targetEntity=Meet::class, mappedBy="Phase")
     * @ORM\OrderBy({"time" = "ASC"})
     */
    private $meets;

    /**
     * @ORM\ManyToOne(targetEntity=TypePhase::class)
     */
    private $Type;

    /**
     * @ORM\ManyToOne(targetEntity=Phase::class)
     */
    private $PhasePrecedente;

    /**
     * @ORM\OneToMany(targetEntity=Poule::class, mappedBy="Phase")
     */
    private $poules;

    /**
     * @ORM\ManyToOne(targetEntity=Phase::class)
     */
    private $PhaseSuivante;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $TempsMatch;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $TempsEntreMatch;

    /**
     * @ORM\OneToMany(targetEntity=Position::class, mappedBy="PhaseFrom")
     */
    private $positions;

    /**
     * @ORM\Column(type="string", length=100, nullable="true")
     */
    private $param;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ordre;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="Phases")
     */
    private $category;

    public function __construct()
    {
        $this->meets = new ArrayCollection();
        $this->poules = new ArrayCollection();
        $this->positions = new ArrayCollection();
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
            $meet->setPhase($this);
        }

        return $this;
    }

    public function removeMeet(Meet $meet): self
    {
        if ($this->meets->removeElement($meet)) {
            // set the owning side to null (unless already changed)
            if ($meet->getPhase() === $this) {
                $meet->setPhase(null);
            }
        }

        return $this;
    }

    public function getType(): ?TypePhase
    {
        return $this->Type;
    }

    public function setType(?TypePhase $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getPhasePrecedente(): ?self
    {
        return $this->PhasePrecedente;
    }

    public function setPhasePrecedente(?self $PhasePrecedente): self
    {
        $this->PhasePrecedente = $PhasePrecedente;

        return $this;
    }

    /**
     * @return Collection<int, Poule>
     */
    public function getPoules(): Collection
    {
        return $this->poules;
    }

    public function addPoule(Poule $poule): self
    {
        if (!$this->poules->contains($poule)) {
            $this->poules[] = $poule;
            $poule->setPhase($this);
        }

        return $this;
    }

    public function removePoule(Poule $poule): self
    {
        if ($this->poules->removeElement($poule)) {
            // set the owning side to null (unless already changed)
            if ($poule->getPhase() === $this) {
                $poule->setPhase(null);
            }
        }

        return $this;
    }

    public function getPhaseSuivante(): ?self
    {
        return $this->PhaseSuivante;
    }

    public function setPhaseSuivante(?self $PhaseSuivante): self
    {
        $this->PhaseSuivante = $PhaseSuivante;

        return $this;
    }

    public function getTempsMatch(): ?int
    {
        return $this->TempsMatch;
    }

    public function setTempsMatch(?int $TempsMatch): self
    {
        $this->TempsMatch = $TempsMatch;

        return $this;
    }

    public function getTempsEntreMatch(): ?int
    {
        return $this->TempsEntreMatch;
    }

    public function setTempsEntreMatch(?int $TempsEntreMatch): self
    {
        $this->TempsEntreMatch = $TempsEntreMatch;

        return $this;
    }

    /**
     * @return Collection<int, Position>
     */
    public function getPositions(): Collection
    {
        return $this->positions;
    }

    public function addPosition(Position $position): self
    {
        if (!$this->positions->contains($position)) {
            $this->positions[] = $position;
            $position->setPhaseFrom($this);
        }

        return $this;
    }

    public function removePosition(Position $position): self
    {
        if ($this->positions->removeElement($position)) {
            // set the owning side to null (unless already changed)
            if ($position->getPhaseFrom() === $this) {
                $position->setPhaseFrom(null);
            }
        }

        return $this;
    }

    public function getParam(): ?string
    {
        return $this->param;
    }

    public function setParam(string $param): self
    {
        $this->param = $param;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
