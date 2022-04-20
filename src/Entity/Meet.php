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
     * @Groups({"matchs","match_detail"})
     */
    private $TeamA;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="fsfsd")
     * @Groups({"matchs","match_detail"})
     */
    private $TeamB;

    /**
     * @ORM\ManyToOne(targetEntity=Field::class, inversedBy="meets")
     * @Groups({"matchs","match_detail"})
     *
     */
    private $Field;

    /**
     * @ORM\ManyToOne(targetEntity=Phase::class, inversedBy="meets")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"matchs","match_detail"})
     */
    private $Phase;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"matchs","match_detail"})
     */
    private $ScoreA;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"matchs","match_detail"})
     */
    private $ScoreB;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"matchs","match_detail"})
     */
    private $PenaltyA;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"matchs","match_detail"})
     */
    private $PenaltyB;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class)
     * @Groups({"matchs","match_detail"})
     */
    private $TeamForfait;



    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"matchs"})
     */
    private $Tour;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $principal;

    /**
     * @ORM\ManyToOne(targetEntity=Poule::class, inversedBy="meets", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Groups({"matchs"})
     */
    private $Poule;

    /**
     * @ORM\ManyToOne(targetEntity=Position::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Groups({"matchs","positions"})
     */
    private $PositionA;

    /**
     * @ORM\ManyToOne(targetEntity=Position::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Groups({"matchs","positions"})
     */
    private $PositionB;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"matchs"})
     */
    private $Name;

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



    public function getTour(): ?int
    {
        return $this->Tour;
    }

    public function setTour(?int $Tour): self
    {
        $this->Tour = $Tour;

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

    public function getMatchs()
    {

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

    public function getPositionA(): ?Position
    {
        return $this->PositionA;
    }

    public function setPositionA(?Position $PositionA): self
    {
        $this->PositionA = $PositionA;

        return $this;
    }

    public function getPositionB(): ?Position
    {
        return $this->PositionB;
    }

    public function setPositionB(?Position $PositionB): self
    {
        $this->PositionB = $PositionB;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(?string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }
}
