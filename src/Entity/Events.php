<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventsRepository")
 */
class Events
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="boolean")
     */
    private $private_status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Participants", mappedBy="event")
     */
    private $participants;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $freePlaces;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrivateStatus(): ?bool
    {
        return $this->private_status;
    }

    public function setPrivateStatus(bool $private_status): self
    {
        $this->private_status = $private_status;

        return $this;
    }

    /**
     * @return Collection|Participants[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(Participants $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setEvent($this);
        }

        return $this;
    }

    public function removeUser(Participants $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getEvent() === $this) {
                $user->setEvent(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
    return $this->author;
    }

    public function getFreePlaces(): ?int
    {
        return $this->freePlaces;
    }

    public function setFreePlaces(?int $freePlaces): self
    {
        $this->freePlaces = $freePlaces;

        return $this;
    }
}
