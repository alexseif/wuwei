<?php

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
class Schedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $est = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $eta = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'], targetEntity: Tasks::class, inversedBy: 'schedule')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tasks $task = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEst(): ?int
    {
        return $this->est;
    }

    public function setEst(int $est): static
    {
        $this->est = $est;

        return $this;
    }

    public function getEta(): ?\DateTimeInterface
    {
        return $this->eta;
    }

    public function setEta(\DateTimeInterface $eta): static
    {
        $this->eta = $eta;

        return $this;
    }

    public function getTask(): ?Tasks
    {
        return $this->task;
    }

    public function setTask(Tasks $task): static
    {
        $this->task = $task;

        return $this;
    }
}
