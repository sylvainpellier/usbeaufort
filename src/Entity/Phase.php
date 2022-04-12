<?php

namespace App\Entity;

use App\Repository\PhaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PhaseRepository::class)
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
     */
    private $meets;

    /**
     * @ORM\ManyToOne(targetEntity=TypePhase::class)
     */
    private $Type;

    /**
     * @ORM\ManyToOne(targetEntity=Phase::class)
     */
    private $PhasePrecente;

    public function __construct()
    {
        $this->meets = new ArrayCollection();
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

    public function getPhasePrecente(): ?self
    {
        return $this->PhasePrecente;
    }

    public function setPhasePrecente(?self $PhasePrecente): self
    {
        $this->PhasePrecente = $PhasePrecente;

        return $this;
    }
}
