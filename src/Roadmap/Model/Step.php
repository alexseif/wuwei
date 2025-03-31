<?php

namespace App\Roadmap\Model;

class Step
{
    public string $title;
    public bool $start = false;
    public bool $end = false;
    public ?Step $beforeStep = null;
    public ?Step $afterStep = null;

    public int $top = 0;
    public int $left = 0;

    public function __construct(string $title, array $options = [])
    {
        $this->setTitle($title);
        $this->setOptions($options);
    }


    public function setOptions(array $options): void
    {
        if (isset($options['start'])) {
            $this->start = $options['start'];
        }

        if (isset($options['end'])) {
            $this->end = $options['end'];
        }

        $this->top = $options['top'] ?? $this->top; // Use provided top or default to 0
        $this->left = $options['left'] ?? $this->left; // Use provided left or default to 0
    }

    public function getOptions(): array
    {
        return [
            'start' => $this->start,
            'end' => $this->end,
        ];
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setStart(bool $start): void
    {
        $this->start = $start;
    }

    public function isStart(): bool
    {
        return $this->start;
    }

    public function setEnd(bool $end): void
    {
        $this->end = $end;
    }

    public function isEnd(): bool
    {
        return $this->end;
    }

    public function setBeforeStep(?Step $step): void
    {
        $this->beforeStep = $step;
    }

    public function getBeforeStep(): ?Step
    {
        return $this->beforeStep;
    }

    public function setAfterStep(?Step $step): void
    {
        $this->afterStep = $step;
    }

    public function getAfterStep(): ?Step
    {
        return $this->afterStep;
    }

    public function setPosition(int $top, int $left): void
    {
        $this->top = $top;
        $this->left = $left;
    }

    public function getPosition(): array
    {
        return ['top' => $this->top, 'left' => $this->left];
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'start' => $this->start,
            'end' => $this->end,
            'top' => $this->top,
            'left' => $this->left,
        ];
    }
}
