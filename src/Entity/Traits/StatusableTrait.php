<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Exception\InvalidArgumentException;

trait StatusableTrait
{

    public const STATUSES = [
      'archive' => 'archive',
      'active' => 'active',
    ];

    #[ORM\Column(length: 255)]
    private ?string $status = self::STATUSES['active'];

    #[ORM\Column]
    private ?bool $enabled = true;

    private function isValidStatus(?string $status): bool
    {
        return in_array($status, self::STATUSES);
    }

    public function setStatus(?string $status): void
    {
        if (!$this->isValidStatus($status)) {
            throw new InvalidArgumentException('Invalid status value');
        }
        $this->status = $status;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }


    public function archive(): void
    {
        $this->setStatus(self::STATUSES['archive']);
    }

    public function activate(): void
    {
        $this->setStatus(self::STATUSES['active']);
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(?bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function enable(): void
    {
        $this->setEnabled(true);
    }

    public function disable(): void
    {
        $this->setEnabled(false);
    }

}