<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ORM\Table(name="usb_groups")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"team","matchs"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     * @Groups({"category","team"})
     */
    private $Name;

    /**
     * @ORM\OneToMany(targetEntity=Team::class, mappedBy="Category", orphanRemoval=true)
     * @Groups({"category_detail"})
     */
    private $teams;

    /**
     * @ORM\ManyToMany(targetEntity=Phase::class)
     * @ORM\JoinTable(name="usb_phases_categories")
     */
    private $Phases;

    /**
     * @ORM\ManyToOne(targetEntity=Phase::class)
     */
    private $PhaseEnCours;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
        $this->Phases = new ArrayCollection();
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
     * @return Collection<int, Team>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
            $team->setCategory($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->removeElement($team)) {
            // set the owning side to null (unless already changed)
            if ($team->getCategory() === $this) {
                $team->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Phase>
     */
    public function getPhases(): Collection
    {
        return $this->Phases;
    }

    public function addPhase(Phase $phase): self
    {
        if (!$this->Phases->contains($phase)) {
            $this->Phases[] = $phase;
        }

        return $this;
    }

    public function removePhase(Phase $phase): self
    {
        $this->Phases->removeElement($phase);

        return $this;
    }

    public function getPhaseEnCours(): ?Phase
    {
        return $this->PhaseEnCours;
    }

    public function setPhaseEnCours(?Phase $PhaseEnCours): self
    {
        $this->PhaseEnCours = $PhaseEnCours;

        return $this;
    }

    public function __toString() : string
    {
        return $this->getName();
    }
}
