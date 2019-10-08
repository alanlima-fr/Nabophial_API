<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RelationRepository")
 */
class Relation
{
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\AppUser", inversedBy="relations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $firstUser;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\AppUser")
     * @ORM\JoinColumn(nullable=false)
     */
    private $SecondUser;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $initiator;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RelationStatus")
     */
    private $relationStatus;

    public function getFirstUser(): ?AppUser
    {
        return $this->firstUser;
    }

    public function setFirstUser(?AppUser $firstUser): self
    {
        $this->firstUser = $firstUser;

        return $this;
    }

    public function getSecondUser(): ?AppUser
    {
        return $this->SecondUser;
    }

    public function setSecondUser(?AppUser $SecondUser): self
    {
        $this->SecondUser = $SecondUser;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInitiator(): ?int
    {
        return $this->initiator;
    }

    public function setInitiator(?int $initiator): self
    {
        $this->initiator = $initiator;

        return $this;
    }

    public function getRelationStatus(): ?RelationStatus
    {
        return $this->relationStatus;
    }

    public function setRelationStatus(?RelationStatus $relationStatus): self
    {
        $this->relationStatus = $relationStatus;

        return $this;
    }


}
