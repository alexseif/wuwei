<?php

namespace App\Service;

class DurationReportService
{
    public function formatMinutesToHours(int|null $minutes): string
    {
        return sprintf('%02d:%02d', $minutes / 60, $minutes % 60);
    }

    public function generateReport(array $durations): array
    {
        $report = [];
        foreach ($durations as $key => $minutes) {
            $report[$key] = $this->formatMinutesToHours($minutes);
        }
        return $report;
    }

    public function calculatePercentageDifference(int|null $current, int|null $previous): float
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return (($current - $previous) / $previous) * 100;
    }
}
