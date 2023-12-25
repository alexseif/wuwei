<?php

namespace App\Entity;

use App\Entity\Traits\TaggableTrait;
use App\Repository\TimeSystemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: TimeSystemRepository::class)]
class TimeSystem
{

    use TimestampableEntity;
    use TaggableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $from_time = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $to_time = null;

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

    public function getFromTime(): ?\DateTimeInterface
    {
        return $this->from_time;
    }

    public function setFromTime(?\DateTimeInterface $from_time): static
    {
        $this->from_time = $from_time;

        return $this;
    }

    public function getToTime(): ?\DateTimeInterface
    {
        return $this->to_time;
    }

    public function setToTime(?\DateTimeInterface $to_time): static
    {
        $this->to_time = $to_time;

        return $this;
    }

}
