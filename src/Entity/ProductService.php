<?php

namespace App\Entity;

use App\Repository\ProductServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: ProductServiceRepository::class)]
class ProductService
{

    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $billingCycle = null;

    /**
     * @var Collection<int, AccountServiceAssignment>
     */
    #[ORM\OneToMany(mappedBy: 'productService', targetEntity: AccountServiceAssignment::class)]
    private Collection $accountServiceAssignments;

    public function __construct()
    {
        $this->accountServiceAssignments = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getBillingCycle(): ?string
    {
        return $this->billingCycle;
    }

    public function setBillingCycle(?string $billingCycle): static
    {
        $this->billingCycle = $billingCycle;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection<int, AccountServiceAssignment>
     */
    public function getAccountServiceAssignments(): Collection
    {
        return $this->accountServiceAssignments;
    }

    public function addAccountServiceAssignment(
      AccountServiceAssignment $accountServiceAssignment
    ): static {
        if (!$this->accountServiceAssignments->contains(
          $accountServiceAssignment
        )) {
            $this->accountServiceAssignments->add($accountServiceAssignment);
            $accountServiceAssignment->setProductService($this);
        }

        return $this;
    }

    public function removeAccountServiceAssignment(
      AccountServiceAssignment $accountServiceAssignment
    ): static {
        if ($this->accountServiceAssignments->removeElement(
          $accountServiceAssignment
        )) {
            // set the owning side to null (unless already changed)
            if ($accountServiceAssignment->getProductService() === $this) {
                $accountServiceAssignment->setProductService(null);
            }
        }

        return $this;
    }

}
