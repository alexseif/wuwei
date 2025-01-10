<?php

namespace App\Entity;

use App\Repository\TasksRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: TasksRepository::class)]
class Tasks
{
    use TimestampableEntity;

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
    private ?string $task = null;

    #[ORM\Column(name: 'torder')]
    private ?int $order = null;

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

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $completedAt = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn()]
    private ?TaskLists $taskList = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?WorkLog $workLog = null;

    #[ORM\Column]
    private ?bool $workLoggable = true;

    /**
     * @var string[]
     */
    public array $priorityName;

    /**
     * @var string[]
     */
    public array $urgencyName;

    public function __construct()
    {
        $this->order = 0;
        $this->priority = self::NORMAL_PRIORITY;
        $this->urgency = self::NORMAL_URGENCY;
        $this->workLoggable = true;
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

    public function getTask(): ?string
    {
        return $this->task;
    }
    public function getName(): ?string
    {
        return $this->getTask();
    }

    public function setTask(string $task): static
    {
        $this->task = $task;

        return $this;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function setOrder(int $order): static
    {
        $this->order = $order;

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

    public function getPriorityName(): string
    {
        return $this->priorityName[$this->priority];
    }

    public function getUrgency(): ?int
    {
        return $this->urgency;
    }

    public function getUrgencyName(): string
    {
        return $this->urgencyName[$this->urgency];
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

    public function getCompletedAt(): ?\DateTimeInterface
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?\DateTimeInterface $completedAt): static
    {
        $this->completedAt = $completedAt;

        return $this;
    }

    public function getTaskList(): ?TaskLists
    {
        return $this->taskList;
    }

    public function setTaskList(?TaskLists $taskList): static
    {
        $this->taskList = $taskList;

        return $this;
    }

    public function getWorkLog(): ?WorkLog
    {
        return $this->workLog;
    }

    public function setWorkLog(?WorkLog $workLog): static
    {
        $this->workLog = $workLog;

        return $this;
    }

    public function isWorkLoggable(): ?bool
    {
        return $this->workLoggable;
    }

    public function setWorkLoggable(bool $workLoggable): static
    {
        $this->workLoggable = $workLoggable;

        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'task' => $this->task,
            'order' => $this->order,
            'priority' => $this->priority,
            'urgency' => $this->urgency,
            'duration' => $this->duration,
            'est' => $this->est,
            'eta' => ($this->eta) ? $this->eta->format('Y-m-d H:i:s') : '',
            'completed' => $this->completed,
            'completedAt' => ($this->completedAt) ? $this->completedAt->format('Y-m-d H:i:s') : '',
            'taskList' => $this->taskList->getId(),
            'workLog' => $this->workLog,
            'workLoggable' => $this->workLoggable,
        ];
    }
}
