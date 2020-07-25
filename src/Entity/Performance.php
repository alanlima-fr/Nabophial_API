<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PerformanceRepository")
 */
class Performance
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypePerformance")
     *
     * @var TypePerformance
     */
    private $typePerformance;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sport")
     *
     * @var Sport
     */
    private $sport;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTypePerformance(): ?TypePerformance
    {
        return $this->typePerformance;
    }

    public function setTypePerformance(?TypePerformance $typePerformance): self
    {
        $this->typePerformance = $typePerformance;

        return $this;
    }

    public function getSport(): ?Sport
    {
        return $this->sport;
    }

    public function setSport(?Sport $sport): self
    {
        $this->sport = $sport;

        return $this;
    }
}
