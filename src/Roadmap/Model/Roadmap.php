<?php

namespace App\Roadmap\Model;

class Roadmap
{
    public string $title;

    public array $steps = [];

    public function addStep(Step $step): void
    {
        $lastStep = end($this->steps); // Get the last step, if any

        if ($lastStep) {
            $lastStep->setAfterStep($step); // Link the last step to the new step
            $step->setBeforeStep($lastStep); // Link the new step to the last step

            // Calculate position if not explicitly set
            if ($step->top === 0 && $step->left === 0) {
                $step->setPosition($lastStep->top + (24 + (16 * 2) + (2 * 1) + 20), $lastStep->left); // Example vertical offset of 150px
            }
        }

        $this->steps[] = $step; // Add the new step to the roadmap
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'steps' => array_map(fn($step) => $step->toArray(), $this->steps),
        ];
    }
}
