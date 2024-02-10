<?php

namespace App\Entity;

use App\Entity\Traits\TaggableTrait;
use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;


#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{

    use TimestampableEntity;
    use TaggableTrait;

    public const LOW_PRIORITY = -1;

    public const NORMAL_PRIORITY = 0;

    public const HIGH_PRIORITY = 1;

    public const NORMAL_URGENCY = 0;

    public const HIGH_URGENCY = 1;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Gedmo\SortablePosition]
    #[ORM\Column(nullable: true)]
    private ?int $position = null;

    #[ORM\Column]
    private ?int $priority = null;

    #[ORM\Column]
    private ?int $urgency = null;

    #[ORM\Column(nullable: true)]
    private ?int $duration = null;

    #[ORM\Column(nullable: true)]
    private ?int $est = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $eta = null;

    #[ORM\Column]
    private ?bool $completed = false;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $completedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dueAt = null;

    #[ORM\Column(nullable: true)]
    private ?array $details = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?Tag $type = null;

    /**
     * @var string[]
     */
    public $priorityName;

    /**
     * @var string[]
     */
    public $urgencyName;


    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->priority = Task::NORMAL_PRIORITY;
        $this->urgency = Task::NORMAL_URGENCY;
        $this->priorityName = [
          -1 => 'Low',
          0 => 'Normal',
          1 => 'Important',
        ];
        $this->urgencyName = [
          0 => 'Normal',
          1 => 'Urgent',
        ];
    }

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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getUrgency(): ?int
    {
        return $this->urgency;
    }

    public function setUrgency(int $urgency): static
    {
        $this->urgency = $urgency;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getEst(): ?int
    {
        return $this->est;
    }

    public function setEst(?int $est): static
    {
        $this->est = $est;

        return $this;
    }

    public function getEta(): ?\DateTimeInterface
    {
        return $this->eta;
    }

    public function setEta(?\DateTimeInterface $eta): static
    {
        $this->eta = $eta;

        return $this;
    }

    public function isCompleted(): ?bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): static
    {
        $this->completed = $completed;

        return $this;
    }

    public function getCompletedAt(): ?\DateTimeImmutable
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?\DateTimeImmutable $completedAt): static
    {
        $this->completedAt = $completedAt;

        return $this;
    }

    public function getDueAt(): ?\DateTimeImmutable
    {
        return $this->dueAt;
    }

    public function setDueAt(?\DateTimeImmutable $dueAt): static
    {
        $this->dueAt = $dueAt;

        return $this;
    }

    public function getDetails(): ?array
    {
        return $this->details;
    }

    public function setDetails(?array $details): static
    {
        $this->details = $details;

        return $this;
    }

    public function getDescription(): ?string
    {
        return (isset($this->details['description'])) ? $this->details['description'] : null;
    }

    public function setDescription(?string $description): static
    {
        $this->details['description'] = $description;

        return $this;
    }

    public function getType(): ?Tag
    {
        return $this->type;
    }

    public function setType(?Tag $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

}
