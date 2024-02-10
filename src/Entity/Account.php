<?php

namespace App\Entity;

use App\Entity\Traits\StatusableTrait;
use App\Entity\Traits\TaggableTrait;
use App\Repository\AccountRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{
    use TimestampableEntity;
    use TaggableTrait;
    use StatusableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'], inversedBy: 'account')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tag $AccountTag = null;

    #[ORM\ManyToOne(inversedBy: 'accounts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $Client = null;

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

    public function getAccountTag(): ?Tag
    {
        return $this->AccountTag;
    }

    public function setAccountTag(Tag $AccountTag): static
    {
        $this->AccountTag = $AccountTag;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->Client;
    }

    public function setClient(?Client $Client): static
    {
        $this->Client = $Client;

        return $this;
    }
}
