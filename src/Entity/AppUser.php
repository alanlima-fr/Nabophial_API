<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AppUserRepository")
 */
class AppUser implements UserInterface, HistoryEntityInterface
{
    use HistoryEntityTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Annotation\Groups({"id", "app_user"})
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="password", type="string", nullable=true)
     * @Annotation\Groups({"null"})
     *
     * @var string
     */
    protected $password;

    /** @var ?string */
    protected $plainPassword;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Annotation\Groups({"app_user"})
     *
     * @var string
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Annotation\Groups({"app_user"})
     *
     * @var string
     */
    private $firstName;

    /**
     * @ORM\Column(type="date")
     * @Annotation\Groups({"app_user"})
     *
     * @var DateTimeInterface
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Annotation\Groups({"app_user"})
     *
     * @var string
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Annotation\Groups({"app_user"})
     *
     * @var string
     */
    private $number;

    /**
     * True = male
     * False = female.
     *
     * @ORM\Column(type="boolean")
     * @Annotation\Groups({"app_user"})
     *
     * @var bool
     */
    private $male;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Sport")
     * @Annotation\Groups({"app_user"})
     *
     * @var Collection<int, Sport>
     */
    private $preference;

    /**
     * @ORM\Column(type="array")
     * @Annotation\Groups({"null"})
     *
     * @var array<int, string>
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Relation", mappedBy="firstUser")
     * @Annotation\Groups({"app_user"})
     *
     * @var Collection<int, Relation>
     */
    private $relations;

    public function __construct()
    {
        $this->preference = new ArrayCollection();
        $this->relations = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        if (!$this->roles) {
            $this->roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    /**
     * @param array<string> $roles
     *
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        if (!$this->roles) {
            $this->roles[] = 'ROLE_USER';
        }

        return $this;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getBirthday(): DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return Collection<int, Sport>
     */
    public function getPreference(): Collection
    {
        return $this->preference;
    }

    public function addPreference(Sport $preference): self
    {
        if (!$this->preference->contains($preference)) {
            $this->preference[] = $preference;
        }

        return $this;
    }

    public function removePreference(Sport $preference): self
    {
        if ($this->preference->contains($preference)) {
            $this->preference->removeElement($preference);
        }

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function isMale(): ?bool
    {
        return $this->male;
    }

    public function setMale(?bool $male): self
    {
        $this->male = $male;

        return $this;
    }

    /**
     * @return Collection<int, Relation>
     */
    public function getRelations(): Collection
    {
        return $this->relations;
    }

    public function addRelation(Relation $relation): self
    {
        if (!$this->relations->contains($relation)) {
            $this->relations[] = $relation;
            $relation->setFirstUser($this);
        }

        return $this;
    }

    public function removeRelation(Relation $relation): self
    {
        if ($this->relations->contains($relation)) {
            $this->relations->removeElement($relation);
            // set the owning side to null (unless already changed)
            if ($relation->getFirstUser() === $this) {
                $relation->setFirstUser(null);
            }
        }

        return $this;
    }

    public function isNew(): bool
    {
        return null === $this->getId();
    }

    public function isAdmin(): bool
    {
        return $this->isGranted('ROLE_ADMIN');
    }

    public function isGranted(string $role): bool
    {
        return in_array($role, $this->roles, true);
    }
}
