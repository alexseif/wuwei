<?php

namespace App\Entity;

use App\Repository\DailyRepository;
use App\Utility\DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DailyRepository::class)]
class Daily
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    //TODO: Consider date field
    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'daily', targetEntity: Item::class, cascade: ["remove"], fetch: "EAGER")]
    private Collection $items;

    public function __construct()
    {
        $this->name = DateTime::getToday();
        $this->items = new ArrayCollection();
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

    /**
     * @return Collection<int, Item>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setDaily($this);
        }

        return $this;
    }

    public function removeItem(Item $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getDaily() === $this) {
                $item->setDaily(null);
            }
        }

        return $this;
    }

}
