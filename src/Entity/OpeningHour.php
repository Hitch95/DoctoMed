<?php

namespace App\Entity;

use App\Repository\OpeningHourRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OpeningHourRepository::class)]
class OpeningHour
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $weekday = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $weekdayNumber = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $openingTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $closingTime = null;

    #[ORM\ManyToOne(inversedBy: 'openingHours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?HealthcareCenter $healthcareCenter = null;

    public function getFormattedTime(): string
    {
        if ($this->getOpeningTime() === null || $this->getClosingTime() === null) {
            return 'Fermé';
        }
        return $this->getOpeningTime()->format('G') . 'h - ' . $this->getClosingTime()->format('G') . 'h';
    }

    public function isToday(): bool
    {
        return $this->getWeekdayNumber() === (int) (new \DateTime())->format('N');
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeekday(): ?string
    {
        return $this->weekday;
    }

    public function setWeekday(string $weekday): static
    {
        $this->weekday = $weekday;

        return $this;
    }

    public function getWeekdayNumber(): ?int
    {
        return $this->weekdayNumber;
    }

    public function setWeekdayNumber(int $weekdayNumber): static
    {
        $this->weekdayNumber = $weekdayNumber;

        return $this;
    }

    public function getOpeningTime(): ?\DateTimeInterface
    {
        return $this->openingTime;
    }

    public function setOpeningTime(?\DateTimeInterface $openingTime): static
    {
        $this->openingTime = $openingTime;

        return $this;
    }

    public function getClosingTime(): ?\DateTimeInterface
    {
        return $this->closingTime;
    }

    public function setClosingTime(?\DateTimeInterface $closingTime): static
    {
        $this->closingTime = $closingTime;

        return $this;
    }

    public function getHealthcareCenter(): ?HealthcareCenter
    {
        return $this->healthcareCenter;
    }

    public function setHealthcareCenter(?HealthcareCenter $healthcareCenter): static
    {
        $this->healthcareCenter = $healthcareCenter;

        return $this;
    }
}