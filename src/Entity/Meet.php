<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=Maepository::class)
 * @ORM\Table(name="usb_meets")
 */
class Meet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"team","matchs"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="meets")
     * @Groups({"matchs"})
     */
    private $TeamA;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="fsfsd")
     * @Groups({"matchs"})
     */
    private $TeamB;

    /**
     * @ORM\ManyToOne(targetEntity=Field::class, inversedBy="meets")
     * @Groups({"matchs"})
     *
     */
    private $Field;

    /**
     * @ORM\ManyToOne(targetEntity=Phase::class, inversedBy="meets")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"matchs"})
     */
    private $Phase;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"matchs"})
     */
    private $ScoreA;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"matchs"})
     */
    private $ScoreB;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"matchs"})
     */
    private $PenaltyA;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"matchs"})
     */
    private $PenaltyB;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class)
     * @Groups({"matchs"})
     */
    private $TeamForfait;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $Poule;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"matchs"})
     */
    private $Tour;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeamA(): ?Team
    {
        return $this->TeamA;
    }

    public function setTeamA(?Team $TeamA): self
    {
        $this->TeamA = $TeamA;

        return $this;
    }

    public function getTeamB(): ?Team
    {
        return $this->TeamB;
    }

    public function setTeamB(?Team $TeamB): self
    {
        $this->TeamB = $TeamB;

        return $this;
    }

    public function getField(): ?Field
    {
        return $this->Field;
    }

    public function setField(?Field $Field): self
    {
        $this->Field = $Field;

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

    public function getScoreA(): ?int
    {
        return $this->ScoreA;
    }

    public function setScoreA(?int $ScoreA): self
    {
        $this->ScoreA = $ScoreA;

        return $this;
    }

    public function getScoreB(): ?int
    {
        return $this->ScoreB;
    }

    public function setScoreB(?int $ScoreB): self
    {
        $this->ScoreB = $ScoreB;

        return $this;
    }

    public function getPenaltyA(): ?int
    {
        return $this->PenaltyA;
    }

    public function setPenaltyA(?int $PenaltyA): self
    {
        $this->PenaltyA = $PenaltyA;

        return $this;
    }

    public function getPenaltyB(): ?int
    {
        return $this->PenaltyB;
    }

    public function setPenaltyB(?int $PenaltyB): self
    {
        $this->PenaltyB = $PenaltyB;

        return $this;
    }

    public function getTeamForfait(): ?Team
    {
        return $this->TeamForfait;
    }

    public function setTeamForfait(?Team $TeamForfait): self
    {
        $this->TeamForfait = $TeamForfait;

        return $this;
    }

    public function getPoule(): ?string
    {
        return $this->Poule;
    }

    public function setPoule(?string $Poule): self
    {
        $this->Poule = $Poule;

        return $this;
    }

    public function getTour(): ?int
    {
        return $this->Tour;
    }

    public function setTour(?int $Tour): self
    {
        $this->Tour = $Tour;

        return $this;
    }
}
