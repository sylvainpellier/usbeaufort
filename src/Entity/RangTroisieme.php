<?php

namespace App\Entity;

use App\Repository\RangTroisiemeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RangTroisiemeRepository::class)
 * @ORM\Table(name="usb_rangs_troisieme")
 */
class RangTroisieme
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Phase::class, inversedBy="rangTroisiemes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Phase;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $Team;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Rang;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTeam(): ?Team
    {
        return $this->Team;
    }

    public function setTeam(?Team $Team): self
    {
        $this->Team = $Team;

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
}
