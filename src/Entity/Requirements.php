<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="requirements")
 */
#[ORM\Entity]
#[ORM\Table(name: 'requirements')]
class Requirements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\ManyToOne(targetEntity: Proposals::class)]
    #[ORM\JoinColumn(name: 'proposal_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Proposals $proposal;

    #[ORM\Column(type: 'string', length: 255)]
    private string $category;

    #[ORM\Column(type: 'string', columnDefinition: "ENUM('High', 'Medium', 'Low')")]
    private string $priority;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Requirements
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Requirements
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Requirements
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Proposals
     */
    public function getProposal(): Proposals
    {
        return $this->proposal;
    }

    /**
     * @param Proposals $proposal
     * @return Requirements
     */
    public function setProposal(Proposals $proposal): self
    {
        $this->proposal = $proposal;
        return $this;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param string $category
     * @return Requirements
     */
    public function setCategory(string $category): self
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return string
     */
    public function getPriority(): string
    {
        return $this->priority;
    }

    /**
     * @param string $priority
     * @return Requirements
     */
    public function setPriority(string $priority): self
    {
        $this->priority = $priority;
        return $this;
    }
}
