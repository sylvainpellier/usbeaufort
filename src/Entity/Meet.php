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
}
