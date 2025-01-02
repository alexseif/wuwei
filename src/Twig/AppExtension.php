<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use DateTime;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('time_diff', [$this, 'timeDiff']),
        ];
    }

    public function timeDiff($date)
    {
        $now = new DateTime();
        $interval = $now->diff($date);
        $isPast = $now > $date;
        $sign = $isPast ? '-' : '';

        if ($interval->y > 0) {
            return $sign . $interval->y . ' year' . ($interval->y > 1 ? 's' : '');
        } elseif ($interval->m > 0) {
            return $sign . $interval->m . ' month' . ($interval->m > 1 ? 's' : '');
        } elseif ($interval->d > 0) {
            return $sign . $interval->d . ' day' . ($interval->d > 1 ? 's' : '');
        } elseif ($interval->h > 0) {
            return $sign . $interval->h . ' hour' . ($interval->h > 1 ? 's' : '');
        } elseif ($interval->i > 0) {
            return $sign . $interval->i . ' minute' . ($interval->i > 1 ? 's' : '');
        } else {
            return 'just now';
        }
    }
}
