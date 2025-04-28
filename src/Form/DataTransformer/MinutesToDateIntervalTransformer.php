<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class MinutesToDateIntervalTransformer implements DataTransformerInterface
{
    /**
     * Transforms a DateInterval object to an integer (minutes).
     */
    public function transform($value): ?int
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof \DateInterval) {
            throw new TransformationFailedException('Expected a DateInterval.');
        }

        return ($value->h * 60) + $value->i; // Convert hours and minutes to total minutes
    }

    /**
     * Transforms an integer (minutes) to a DateInterval object.
     */
    public function reverseTransform($value): ?\DateInterval
    {
        if ($value === null) {
            return null;
        }

        if (!is_int($value)) {
            throw new TransformationFailedException('Expected an integer.');
        }

        $hours = intdiv($value, 60);
        $minutes = $value % 60;

        return new \DateInterval(sprintf('PT%dH%dM', $hours, $minutes));
    }
}
