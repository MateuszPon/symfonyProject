<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TimetableRepository")
 */
class Timetable
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
    private $status;

    /**
     * @ORM\Column(type="integer")
     */
    private $matchday;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $homeTeam;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $homeTeamScore;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $season;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $awayTeam;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $awayTeamScore;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $IdApiMatch;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bets", mappedBy="eventMatch")
     */
    private $bets;

    public function __construct()
    {
        $this->bets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getMatchday(): ?int
    {
        return $this->matchday;
    }

    public function setMatchday(int $matchday): self
    {
        $this->matchday = $matchday;

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

    public function getHomeTeam(): ?string
    {
        return $this->homeTeam;
    }

    public function setHomeTeam(string $homeTeam): self
    {
        $this->homeTeam = $homeTeam;

        return $this;
    }

    public function getHomeTeamScore(): ?string
    {
        return $this->homeTeamScore;
    }

    public function setHomeTeamScore(?string $homeTeamScore): self
    {
        $this->homeTeamScore = $homeTeamScore;

        return $this;
    }

    public function getSeason(): ?string
    {
        return $this->season;
    }

    public function setSeason(string $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getAwayTeam(): ?string
    {
        return $this->awayTeam;
    }

    public function setAwayTeam(string $awayTeam): self
    {
        $this->awayTeam = $awayTeam;

        return $this;
    }

    public function getAwayTeamScore(): ?string
    {
        return $this->awayTeamScore;
    }

    public function setAwayTeamScore(?string $awayTeamScore): self
    {
        $this->awayTeamScore = $awayTeamScore;

        return $this;
    }

    public function getIdApiMatch(): ?string
    {
        return $this->IdApiMatch;
    }

    public function setIdApiMatch(string $IdApiMatch): self
    {
        $this->IdApiMatch = $IdApiMatch;

        return $this;
    }

    /**
     * @return Collection|Bets[]
     */
    public function getBets(): Collection
    {
        return $this->bets;
    }

    public function addBet(Bets $bet): self
    {
        if (!$this->bets->contains($bet)) {
            $this->bets[] = $bet;
            $bet->setEventMatch($this);
        }

        return $this;
    }

    public function removeBet(Bets $bet): self
    {
        if ($this->bets->contains($bet)) {
            $this->bets->removeElement($bet);
            // set the owning side to null (unless already changed)
            if ($bet->getEventMatch() === $this) {
                $bet->setEventMatch(null);
            }
        }

        return $this;
    }
}
