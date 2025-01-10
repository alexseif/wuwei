<?php

namespace App\Entity;

use App\Repository\WorkLogRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: WorkLogRepository::class)]
class WorkLog
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\Column]
    private ?float $pricePerUnit = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Tasks $task = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPricePerUnit(): ?float
    {
        return $this->pricePerUnit;
    }

    public function setPricePerUnit(float $pricePerUnit): static
    {
        $this->pricePerUnit = $pricePerUnit;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

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
