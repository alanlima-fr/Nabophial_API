<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AppUserRepository")
 */
class AppUser implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", nullable=true)
     */
    protected $password;

    protected $plainPassword;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $number;

    /**
     * True = male
     * False = female
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $male;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Sport")
     */
    private $preference;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $roles = array();

    public function __construct()
    {
        $this->preference = new ArrayCollection();
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
     * @return string
     */
    public function getPassword()
    {
        return (string) $this->password;
    }

    /**
     * @return string|null
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
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

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
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

    public function setPassword(?string $password): self
    {
        $this->password = $password;

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

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        if (!$this->roles) {
            $this->roles[] = 'ROLE_USER';
        }

        return $this;
    }

    /**
     * @return Collection|Sport[]
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

    public function getMale(): ?bool
    {
        return $this->male;
    }

    public function setMale(?bool $male): self
    {
        $this->male = $male;

        return $this;
    }
}
