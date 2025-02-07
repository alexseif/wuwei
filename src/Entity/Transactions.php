<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="transactions")
 */
#[ORM\Entity]
#[ORM\Table(name: 'transactions')]
class Transactions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Payments::class)]
    #[ORM\JoinColumn(name: 'payment_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Payments $payment;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $date;

    #[ORM\Column(type: 'decimal', scale: 2)]
    private float $amount;

    #[ORM\Column(type: 'string', columnDefinition: "ENUM('Income', 'Expense')")]
    private string $type;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $notes;



    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the value of payment
     */
    public function getPayment(): ?Payments
    {
        return $this->payment;
    }

    /**
     * Set the value of payment
     *
     * @param Payments|null $payment
     * @return self
     */
    public function setPayment(?Payments $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get the value of date
     */
    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @param \DateTimeInterface $date
     * @return self
     */
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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
     * Get the value of type
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @param string $type
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of notes
     */
    public function getNotes(): ?string
    {
        return $this->notes;
    }

    /**
     * Set the value of notes
     *
     * @param string|null $notes
     * @return self
     */
    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }
}
