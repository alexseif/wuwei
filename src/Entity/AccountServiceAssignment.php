<?php

namespace App\Entity;

use App\Repository\AccountServiceAssignmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: AccountServiceAssignmentRepository::class)]
class AccountServiceAssignment
{

    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'accountServiceAssignments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Accounts $account = null;

    #[ORM\ManyToOne(inversedBy: 'accountServiceAssignments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductService $productService = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $renewalDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rrule = null;

    #[ORM\Column]
    private ?bool $isComplete = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $note = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getProductService(): ?ProductService
    {
        return $this->productService;
    }

    public function setProductService(?ProductService $productService): static
    {
        $this->productService = $productService;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getRenewalDate(): ?\DateTimeInterface
    {
        return $this->renewalDate;
    }

    public function setRenewalDate(?\DateTimeInterface $renewalDate): static
    {
        $this->renewalDate = $renewalDate;

        return $this;
    }

    public function getRrule(): ?string
    {
        return $this->rrule;
    }

    public function setRrule(?string $rrule): static
    {
        $this->rrule = $rrule;

        return $this;
    }

    public function isComplete(): ?bool
    {
        return $this->isComplete;
    }

    public function setIsComplete(bool $isComplete): static
    {
        $this->isComplete = $isComplete;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function __toString()
    {
        return $this->productService ? $this->productService->getName(
          ) . ' for ' . $this->account->getName() : 'New Assignment';
    }

}
