<?php

namespace App\Entity;

use App\Repository\DashboardTaskListsRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: DashboardTaskListsRepository::class)]
class DashboardTaskLists
{

    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'taskLists_id', referencedColumnName: 'id')]
    private ?TaskLists $taskList = null;

    public function getId(): ?int
    {
        return $this->id;
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
}
