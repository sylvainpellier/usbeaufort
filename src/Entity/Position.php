<?php

namespace App\Entity;

use App\Repository\PositionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PositionRepository::class)
 * @ORM\Table(name="usb_positions")
 */
class Position
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"matchs","poules","poule_detail","positions"})
     */
    private $Rang;

    /**
     * @ORM\ManyToOne(targetEntity=Poule::class, inversedBy="positions",cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Groups({"matchs","poule_detail","positions"})
     */
    private $PouleFrom;

    /**
     * @ORM\ManyToOne(targetEntity=Poule::class, inversedBy="positionsTo",cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Groups({"matchs","poule_detail"})
     */
    private $PouleTo;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"matchs"})
     */
    private $Principal;

    /**
     * @ORM\ManyToOne(targetEntity=Phase::class, inversedBy="positions")
     * @Groups({"matchs"})
     */
    private $PhaseFrom;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"matchs","positions"})
     */
    private $id_string;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"matchs","positions"})
     */
    private $int_param;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRang(): ?int
    {
        return $this->Rang;
    }

    public function setRang(int $Rang): self
    {
        $this->Rang = $Rang;

        return $this;
    }

    public function getPouleFrom(): ?Poule
    {
        return $this->PouleFrom;
    }

    public function setPouleFrom(?Poule $PouleFrom): self
    {
        $this->PouleFrom = $PouleFrom;

        return $this;
    }

    public function getPouleTo(): ?Poule
    {
        return $this->PouleTo;
    }

    public function setPouleTo(?Poule $PouleTo): self
    {
        $this->PouleTo = $PouleTo;

        return $this;
    }

    public function getPrincipal(): ?bool
    {
        return $this->Principal;
    }

    public function setPrincipal(?bool $Principal): self
    {
        $this->Principal = $Principal;

        return $this;
    }

    public function getPhaseFrom(): ?Phase
    {
        return $this->PhaseFrom;
    }

    public function setPhaseFrom(?Phase $PhaseFrom): self
    {
        $this->PhaseFrom = $PhaseFrom;

        return $this;
    }

    public function getIdString(): ?string
    {
        return $this->id_string;
    }

    public function setIdString(?string $id_string): self
    {
        $this->id_string = $id_string;

        return $this;
    }

    public function getIntParam(): ?int
    {
        return $this->int_param;
    }

    public function setIntParam(?int $int_param): self
    {
        $this->int_param = $int_param;

        return $this;
    }
}
