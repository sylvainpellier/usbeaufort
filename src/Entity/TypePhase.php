<?php

namespace App\Entity;

use App\Repository\TypePhaseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypePhaseRepository::class)
 */
class TypePhase
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $Name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $teamByPoule;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $format;

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

    public function getTeamByPoule(): ?int
    {
        return $this->teamByPoule;
    }

    public function setTeamByPoule(?int $teamByPoule): self
    {
        $this->teamByPoule = $teamByPoule;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(?string $format): self
    {
        $this->format = $format;

        return $this;
    }
}
