<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class User
{

    

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $FirstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $JobTitle;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $Enabled;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $UpdateAt;

     /**
     * @ORM\OneToMany(targetEntity=Dish::class, mappedBy="User")
     */
    private $Dish;



    public function __construct() {
        // we set up "created"+"modified"
        $this->setCreatedAt(new \DateTime());
        $this->asupps = new ArrayCollection();
        $this->asupp = new ArrayCollection();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function updateModifiedDatetime() {
        // update the modified time
        $this->setUpdatedAt(new \DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $Username): self
    {
        $this->username = $Username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    public function setFirstName(string $FirstName): self
    {
        $this->FirstName = $FirstName;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->Lastname;
    }

    public function setLastname(string $Lastname): self
    {
        $this->Lastname = $Lastname;

        return $this;
    }

    public function getJobTitle(): ?string
    {
        return $this->JobTitle;
    }

    public function setJobTitle(string $JobTitle): self
    {
        $this->JobTitle = $JobTitle;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->Enabled;
    }

    public function setEnabled(?bool $Enabled): self
    {
        $this->Enabled = $Enabled;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->UpdateAt;
    }

    public function setUpdatedAt(\DateTimeInterface $UpdateAt): self
    {
        $this->UpdateAt = $UpdateAt;

        return $this;
    }

    /**
     * @return Collection|Dish[]
     */
    public function getDish(): Collection
    {
        return $this->Dish;
    }

    public function addDish(Dish $dish): self
    {
        if (!$this->Dish->contains($dish)) {
            $this->Dish[] = $dish;
            $dish->setUser($this);
        }

        return $this;
    }

    public function removeDish(Dish $dish): self
    {
        if ($this->Dish->removeElement($dish)) {
            // set the owning side to null (unless already changed)
            if ($dish->getUser() === $this) {
                $dish>setUser(null);
            }
        }

        return $this;
    }

   
}
