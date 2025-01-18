<?php

namespace App\Service;

class MenuItem
{
    private ?string $path;
    private string $icon;
    private string $label;
    private bool $dropdown;
    private array $items;
    private bool $divider;

    public function __construct(
        ?string $path,
        string $icon,
        string $label,
        bool $dropdown = false,
        array $items = [],
        bool $divider = false
    ) {
        $this->path = $path;
        $this->icon = $icon;
        $this->label = $label;
        $this->dropdown = $dropdown;
        $this->items = $items;
        $this->divider = $divider;
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