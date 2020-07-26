<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentaryRepository")
 */
class Commentary implements HistoryEntityInterface
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Event")
     *
     * @var Event
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AppUser")
     *
     * @var AppUser
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $payload;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): void
    {
        $this->event = $event;
    }

    public function getUser(): AppUser
    {
        return $this->user;
    }

    public function setUser(AppUser $user): void
    {
        $this->user = $user;
    }

    public function getPayload(): string
    {
        return $this->payload;
    }

    public function setPayload(string $payload): void
    {
        $this->payload = $payload;
    }
}
