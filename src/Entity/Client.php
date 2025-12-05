<?php

namespace App\Entity;

use App\Entity\Traits\Status;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    use TimestampableEntity;
    use Status;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $billingOption = [];

    /**
     * @var Collection<int, Accounts>
     */
    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Accounts::class)]
    private Collection $accounts;

    /**
     * @var Collection<int, Contract>
     */
    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Contract::class)]
    private Collection $contracts;

    /**
     * @var Collection<int, Rate>
     */
    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Rate::class)]
    #[ORM\OrderBy(['createdAt' => 'asc'])]
    private Collection $rates;

    public function __construct()
    {
        $this->accounts = new ArrayCollection();
        $this->contracts = new ArrayCollection();
        $this->rates = new ArrayCollection();
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

    public function getBillingOption(): ?array
    {
        return $this->billingOption;
    }

    public function setBillingOption(?array $billingOption): static
    {
        $this->billingOption = $billingOption;

        return $this;
    }

    /**
     * @return Collection<int, Accounts>
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    public function addAccount(Accounts $account): static
    {
        if (!$this->accounts->contains($account)) {
            $this->accounts->add($account);
            $account->setClient($this);
        }

        return $this;
    }

    public function removeAccount(Accounts $account): static
    {
        if ($this->accounts->removeElement($account)) {
            // set the owning side to null (unless already changed)
            if ($account->getClient() === $this) {
                $account->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Contract>
     */
    public function getContracts(): Collection
    {
        return $this->contracts;
    }

    public function addContract(Contract $contract): static
    {
        if (!$this->contracts->contains($contract)) {
            $this->contracts->add($contract);
            $contract->setClient($this);
        }

        return $this;
    }

    public function removeContract(Contract $contract): static
    {
        if ($this->contracts->removeElement($contract)) {
            // set the owning side to null (unless already changed)
            if ($contract->getClient() === $this) {
                $contract->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rate>
     */
    public function getRates(): Collection
    {
        return $this->rates;
    }

    public function addRate(Rate $rate): static
    {
        if (!$this->rates->contains($rate)) {
            $this->rates->add($rate);
            $rate->setClient($this);
        }

        return $this;
    }

    public function removeRate(Rate $rate): static
    {
        if ($this->rates->removeElement($rate)) {
            // set the owning side to null (unless already changed)
            if ($rate->getClient() === $this) {
                $rate->setClient(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name ?? 'New Client'; // Return the field you want displayed
    }
}
