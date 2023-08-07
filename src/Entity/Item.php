<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;

// TODO: document items
#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ItemList $list = null;

    #[ORM\ManyToOne(inversedBy: 'items')]
    private ?Daily $daily = null;

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

    public function getList(): ?ItemList
    {
        return $this->list;
    }

    public function setList(?ItemList $list): static
    {
        $this->list = $list;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getDaily(): ?Daily
    {
        return $this->daily;
    }

    public function setDaily(?Daily $daily): static
    {
        $this->daily = $daily;

        return $this;
    }


}
