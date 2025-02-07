<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="proposals")
 */
#[ORM\Entity]
#[ORM\Table(name: 'proposals')]
class Proposals
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $date;

    #[ORM\Column(type: 'string', length: 50)]
    private string $code;


    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the value of title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

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
     * Get the value of code
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Set the value of code
     *
     * @param string $code
     * @return self
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
