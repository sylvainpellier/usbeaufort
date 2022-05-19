<?php

namespace App\Entity;

use App\Repository\PouleTeamRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PouleTeamRepository::class)
 * @ORM\Table(name="usb_poules_teams2")
 */
class PouleTeam
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Rang;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="teams")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Team;

    /**
     * @ORM\ManyToOne(targetEntity=Poule::class, inversedBy="poules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Poule;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTeam(): ?Team
    {
        return $this->Team;
    }

    public function setTeam(?Team $Team): self
    {
        $this->Team = $Team;

        return $this;
    }

    public function getPoule(): ?Poule
    {
        return $this->Poule;
    }

    public function setPoule(?Poule $Poule): self
    {
        $this->Poule = $Poule;

        return $this;
    }
}
