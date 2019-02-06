<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BetsRepository")
 */
class Bets
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bets")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Timetable", inversedBy="bets")
     */
    private $eventMatch;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_check;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score;

    /**
     * @ORM\Column(type="integer")
     */
    private $homeScore;

    /**
     * @ORM\Column(type="integer")
     */
    private $awayScore;

    /**
     * @ORM\Column(type="boolean")
     */
    private $checkTotal;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEventMatch(): ?Timetable
    {
        return $this->eventMatch;
    }

    public function setEventMatch(?Timetable $eventMatch): self
    {
        $this->eventMatch = $eventMatch;

        return $this;
    }

    public function getIsCheck(): ?bool
    {
        return $this->is_check;
    }

    public function setIsCheck(bool $is_check): self
    {
        $this->is_check = $is_check;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getHomeScore(): ?int
    {
        return $this->homeScore;
    }

    public function setHomeScore(int $homeScore): self
    {
        $this->homeScore = $homeScore;

        return $this;
    }

    public function getAwayScore(): ?int
    {
        return $this->awayScore;
    }

    public function setAwayScore(int $awayScore): self
    {
        $this->awayScore = $awayScore;

        return $this;
    }

    public function getCheckTotal(): ?bool
    {
        return $this->checkTotal;
    }

    public function setCheckTotal(bool $checkTotal): self
    {
        $this->checkTotal = $checkTotal;

        return $this;
    }
}
