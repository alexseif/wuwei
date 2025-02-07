<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="milestones")
 */
#[ORM\Entity]
#[ORM\Table(name: 'milestones')]
class Milestones
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Proposals::class)]
    #[ORM\JoinColumn(name: 'proposal_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Proposals $proposal;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'integer')]
    private int $week;

    #[ORM\Column(type: 'integer')]
    private int $month;

    #[ORM\Column(type: 'string', length: 255)]
    private string $action_item;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $remarks;

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
    public function getProposal()
    {
        return $this->proposal;
    }

    /**
     * Set the value of proposal
     *
     * @return  self
     */
    public function setProposal($proposal)
    {
        $this->proposal = $proposal;

        return $this;
    }

    /**
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of week
     */
    public function getWeek()
    {
        return $this->week;
    }

    /**
     * Set the value of week
     *
     * @return  self
     */
    public function setWeek($week)
    {
        $this->week = $week;

        return $this;
    }

    /**
     * Get the value of month
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set the value of month
     *
     * @return  self
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get the value of action_item
     */
    public function getActionItem()
    {
        return $this->action_item;
    }

    /**
     * Set the value of action_item
     *
     * @return  self
     */
    public function setActionItem($action_item)
    {
        $this->action_item = $action_item;

        return $this;
    }

    /**
     * Get the value of remarks
     */
    public function getRemarks()
    {
        return $this->remarks;
    }

    /**
     * Set the value of remarks
     *
     * @return  self
     */
    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;

        return $this;
    }
}
