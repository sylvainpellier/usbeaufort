<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use function array_merge;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 * @ORM\Table(name="usb_teams")

 */
class Team
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"team","matchs"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"team","matchs"})
     */
    private $Name;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="teams")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"team"})
     */
    private $Category;

    /**
     * @ORM\OneToMany(targetEntity=Meet::class, mappedBy="TeamA",cascade={"persist"})
     * @Groups({"team"})
     * @ORM\OrderBy({"time" = "ASC"})
     */
    private $meetsA;

    /**
     * @ORM\OneToMany(targetEntity=Meet::class, mappedBy="TeamB",cascade={"persist"})
     * @Groups({"team"})
     * @ORM\OrderBy({"time" = "ASC"})
     */
    private $meetsB;

    /**
     * @ORM\ManyToMany(targetEntity=Poule::class, mappedBy="Teams")
     */
    private $poules;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Rang;



    public function __construct()
    {
        $this->meets = new ArrayCollection();
        $this->poules = new ArrayCollection();
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

    public function getCategory(): ?Category
    {
        return $this->Category;
    }

    public function setCategory(?Category $Category): self
    {
        $this->Category = $Category;

        return $this;
    }

    /**
     * @return Collection<int, Meet>
     */
    public function getMeets(): Collection
    {
        return new ArrayCollection(array_merge($this->meetsA->toArray(),$this->meetsB->toArray()));
    }

    public function addMeet(Meet $meet): self
    {
        if (!$this->meets->contains($meet)) {
            $this->meets[] = $meet;
            $meet->setTeamA($this);
        }

        return $this;
    }

    public function removeMeet(Meet $meet): self
    {
        if ($this->meets->removeElement($meet)) {
            // set the owning side to null (unless already changed)
            if ($meet->getTeamA() === $this) {
                $meet->setTeamA(null);
            }
        }

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
            $poule->addTeam($this);
        }

        return $this;
    }

    public function removePoule(Poule $poule): self
    {
        if ($this->poules->removeElement($poule)) {
            $poule->removeTeam($this);
        }

        return $this;
    }

    public function getRang(): ?int
    {
        return $this->Rang;
    }

    public function setRang(?int $Rang): self
    {
        $this->Rang = $Rang;

        return $this;
    }

    public function __toString() : string
    {
        return $this->getName();
    }


}
