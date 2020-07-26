<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event implements HistoryEntityInterface
{
    use HistoryEntityTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTimeInterface
     */
    private $beginAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTimeInterface
     */
    private $endAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int
     */
    private $maxParticipant;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $privateEvent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getBeginAt(): DateTimeInterface
    {
        return $this->beginAt;
    }

    public function setBeginAt(DateTimeInterface $beginAt): void
    {
        $this->beginAt = $beginAt;
    }

    public function getEndAt(): DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(DateTimeInterface $endAt): void
    {
        $this->endAt = $endAt;
    }

    public function getMaxParticipant(): ?int
    {
        return $this->maxParticipant;
    }

    public function setMaxParticipant(?int $maxParticipant): void
    {
        $this->maxParticipant = $maxParticipant;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getPrivateEvent(): ?bool
    {
        return $this->privateEvent;
    }

    public function setPrivateEvent(?bool $privateEvent): void
    {
        $this->privateEvent = $privateEvent;
    }
}
