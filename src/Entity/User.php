<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="app_user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
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
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
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
    private $gender;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Sport", mappedBy="t")
     */
    private $preferance;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $roles = array();

    public function __construct()
    {
        $this->preferance = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

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

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    /**
     * Suppression des données sensibles
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
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

    public function getGender(): ?bool
    {
        return $this->gender;
    }

    public function setGender(?bool $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return Collection|Sport[]
     */
    public function getPreferance(): Collection
    {
        return $this->preferance;
    }

    public function addPreferance(Sport $preferance): self
    {
        if (!$this->preferance->contains($preferance)) {
            $this->preferance[] = $preferance;
            $preferance->addT($this);
        }

        return $this;
    }

    public function removePreferance(Sport $preferance): self
    {
        if ($this->preferance->contains($preferance)) {
            $this->preferance->removeElement($preferance);
            $preferance->removeT($this);
        }

        return $this;
    }

    public function getRoles()
    {
        if (empty($this->roles))
            return ['ROLE_USER'];

        return $this->roles;
    }

    function addRole($role)
    {
        $this->roles[] = $role;

        return $this;
    }
    
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    public function getusername()
    {
        return $this->email;
    }
}
