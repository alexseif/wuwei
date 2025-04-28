<?php

namespace App\Entity;

use App\Entity\Traits\TaggableTrait;
use App\Repository\TimelogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: TimelogRepository::class)]
class Timelog
{
    use TimestampableEntity;
    use TaggableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $start = null;

    #[ORM\Column(nullable: true)]
    private ?\DateInterval $duration = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $log = null;

    public function __construct()
    {
        $this->start = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): static
    {
        $this->start = $start;

        return $this;
    }

    public function getDuration(): ?\DateInterval
    {
        return $this->duration;
    }

    public function setDuration(?\DateInterval $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getLog(): ?string
    {
        return $this->log;
    }

    public function setLog(?string $log): static
    {
        $this->log = $log;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->start->format('Y-m-d H:i:s');
    }

    public function __toString()
    {
        return $this->getName();
    }
}
