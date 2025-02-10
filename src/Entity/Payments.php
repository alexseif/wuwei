<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="payments")
 */
#[ORM\Entity]
#[ORM\Table(name: 'payments')]
class Payments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Proposals::class)]
    #[ORM\JoinColumn(name: 'proposal_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Proposals $proposal;

    #[ORM\ManyToOne(targetEntity: Milestones::class)]
    #[ORM\JoinColumn(name: 'milestone_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Milestones $milestone;

    #[ORM\Column(type: 'string', length: 255)]
    private string $description;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $due_date;

    #[ORM\Column(type: 'decimal', scale: 2)]
    private float $amount;

    #[ORM\Column(type: 'string', length: 255)]
    private string $status;

    public const STATUS_PENDING = 'Pending';
    public const STATUS_PAID = 'Paid';
    public const STATUS_OVERDUE = 'Overdue';

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of proposal
     */
    public function getProposal(): Proposals
    {
        return $this->proposal;
    }

    /**
     * Set the value of proposal
     *
     * @param Proposals $proposal
     * @return self
     */
    public function setProposal(Proposals $proposal): self
    {
        $this->proposal = $proposal;

        return $this;
    }

    /**
     * Get the value of milestone
     */
    public function getMilestone(): ?Milestones
    {
        return $this->milestone;
    }

    /**
     * Set the value of milestone
     *
     * @param Milestones|null $milestone
     * @return self
     */
    public function setMilestone(?Milestones $milestone): self
    {
        $this->milestone = $milestone;

        return $this;
    }

    /**
     * Get the value of description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param string $description
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of due_date
     */
    public function getDueDate(): \DateTimeInterface
    {
        return $this->due_date;
    }

    /**
     * Set the value of due_date
     *
     * @param \DateTimeInterface $due_date
     * @return self
     */
    public function setDueDate(\DateTimeInterface $due_date): self
    {
        $this->due_date = $due_date;

        return $this;
    }

    /**
     * Get the value of amount
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Set the value of amount
     *
     * @param float $amount
     * @return self
     */
    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @param string $status
     * @return self
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
