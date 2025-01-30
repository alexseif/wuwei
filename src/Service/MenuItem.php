<?php

namespace App\Service;

class MenuItem
{
    public function __construct(private readonly ?string $path, private readonly string $icon, private readonly string $label, private readonly bool $dropdown = false, private readonly array $items = [], private readonly bool $divider = false)
    {
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function isDropdown(): bool
    {
        return $this->dropdown;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function isDivider(): bool
    {
        return $this->divider;
    }
}