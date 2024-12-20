<?php

namespace App\Entity;

use App\Repository\TaskListsRepository;
use App\Repository\TasksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Knp\Component\Pager\PaginatorInterface;

#[ORM\Entity(repositoryClass: TaskListsRepository::class)]
class TaskLists
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'taskLists')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Accounts $account = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    /**
     * @var Collection<int, Tasks>
     */
    #[ORM\OneToMany(mappedBy: 'taskList', targetEntity: Tasks::class, cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['completed' => 'ASC', 'order' => 'ASC'])]
    private Collection $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->status = 'start';
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

    public function getAccount(): ?Accounts
    {
        return $this->account;
    }

    public function setAccount(?Accounts $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Tasks>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Tasks $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setTaskList($this);
        }

        return $this;
    }

    public function removeTask(Tasks $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getTaskList() === $this) {
                $task->setTaskList(null);
            }
        }

        return $this;
    }
}
