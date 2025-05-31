<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;


/**
 *  repository interacts with the 'task_duration_per_day' view.
 */
class TaskDurationPerDayRepository
{
    private readonly Connection $connection;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->connection = $entityManager->getConnection();
    }
    public function getTotal(): int|null
    {
        return $this->fetchSingleValue('SELECT SUM(durationSum) as durationSum FROM task_duration_per_day');
    }

    public function getToday(): int|null
    {
        return $this->fetchSingleValue('SELECT SUM(durationSum) as durationSum FROM task_duration_per_day WHERE completedDate = CURDATE()');
    }

    public function getTodayLastYear(): float|null
    {
        return $this->fetchSingleValue('SELECT SUM(durationSum) as durationSum FROM task_duration_per_day WHERE YEAR(completedDate) = (CURDATE() - INTERVAL 1 YEAR)');
    }

    public function getYesterday(): int|null
    {
        return $this->fetchSingleValue('SELECT SUM(durationSum) as durationSum FROM task_duration_per_day WHERE completedDate = CURDATE() - INTERVAL 1 DAY');
    }

    public function getWeek(): int|null
    {
        return $this->fetchSingleValue('SELECT SUM(durationSum) as durationSum FROM task_duration_per_day WHERE YEARWEEK(completedDate) = YEARWEEK(CURDATE()) AND YEAR(completedDate) = YEAR(CURDATE())');
    }

    public function getWeekLastYear(): int|null
    {
        return $this->fetchSingleValue('SELECT SUM(durationSum) as durationSum FROM task_duration_per_day WHERE YEARWEEK(completedDate) = YEARWEEK(CURDATE() - INTERVAL 1 YEAR) AND YEAR(completedDate) = YEAR(CURDATE() - INTERVAL 1 YEAR)');
    }

    public function getMonth(): int|null
    {
        return $this->fetchSingleValue('SELECT SUM(durationSum) as durationSum FROM task_duration_per_day WHERE MONTH(completedDate) = MONTH(CURDATE()) AND YEAR(completedDate) = YEAR(CURDATE())');
    }

    public function getMonthLastYear(): int|null
    {
        return $this->fetchSingleValue('SELECT SUM(durationSum) as durationSum FROM task_duration_per_day WHERE YEAR(completedDate) = YEAR(CURDATE() - INTERVAL 1 YEAR) AND MONTH(completedDate) = MONTH(CURDATE())');
    }

    public function getQuarter(): int|null
    {
        return $this->fetchSingleValue('SELECT SUM(durationSum) as durationSum FROM task_duration_per_day WHERE QUARTER(completedDate) = QUARTER(CURDATE())');
    }

    public function getQuarterLastYear(): int|null
    {
        return $this->fetchSingleValue('SELECT SUM(durationSum) as durationSum FROM task_duration_per_day WHERE YEAR(completedDate) = YEAR(CURDATE() - INTERVAL 1 YEAR) AND QUARTER(completedDate) = QUARTER(CURDATE())');
    }

    public function getAverageDurationPerDay()
    {
        return $this->fetchSingleValue('SELECT AVG(durationSum) as durationSum FROM task_duration_per_day');
    }

    private function fetchSingleValue(string $sql): float|int|null
    {
        $stmt = $this->connection->prepare($sql);
        $resultSet = $stmt->executeQuery();

        return $resultSet->fetchOne();
    }

    public function getTotalPerDayForAYear(): array
    {
        $results =  $this->fetchTable('SELECT SUM(durationSum) as totalMinutes, Date(completedDate) as day FROM task_duration_per_day WHERE completedDate BETWEEN (CURDATE() - INTERVAL 1 YEAR) AND CURDATE() GROUP BY day ORDER BY day ASC');
        $yearData = [];
        foreach ($results as $result) {
            $date = new \DateTime($result['day']);
            $dayName = $date->format('Y-m-d'); // Get the day name (e.g., "Sunday")
            $totalMinutes = $result['totalMinutes'];
            $yearData[$dayName] =  $totalMinutes;
        }
        return $yearData;
    }

    public function getTotalPerDayForAWeek(): array
    {
        $results =  $this->fetchTable('SELECT SUM(durationSum) as totalMinutes, Date(completedDate) as day FROM task_duration_per_day WHERE completedDate BETWEEN (CURDATE() - INTERVAL 1 WEEK) AND CURDATE() GROUP BY day ORDER BY day ASC');
        $dailyGoals = [
            'Sunday' => 240,
            'Monday' => 240,
            'Tuesday' => 240,
            'Wednesday' => 240,
            'Thursday' => 240,
            'Friday' => 0,
            'Saturday' => 0,
        ];

        // Format the results into percentages
        $weekData = [];
        foreach ($dailyGoals as $day => $goal) {
            $weekData[$day] = 0; // Default to 0%
        }

        foreach ($results as $result) {
            $date = new \DateTime($result['day']);
            $dayName = $date->format('l'); // Get the day name (e.g., "Sunday")
            $totalMinutes = $result['totalMinutes'];
            $goal = $dailyGoals[$dayName];
            $weekData[$dayName] =  round(($totalMinutes / (($goal) ? $goal : 240)) * 100, 2);
        }

        return $weekData;
    }

    public function getTotalPerDayForAMonth(): array
    {
        $results =  $this->fetchTable('SELECT SUM(durationSum) as totalMinutes, Date(completedDate) as day FROM task_duration_per_day WHERE completedDate BETWEEN (CURDATE() - INTERVAL 1 MONTH) AND CURDATE() GROUP BY day ORDER BY day ASC');
        $monthData = [];
        foreach ($results as $result) {
            $date = new \DateTime($result['day']);
            $dayName = $date->format('Y-m-d'); // Get the day name (e.g., "Sunday")
            $totalMinutes = $result['totalMinutes'];
            $monthData[$dayName] =  $totalMinutes;
        }
        return $monthData;
    }

    public function fetchTable(string $sql): array
    {
        $stmt = $this->connection->prepare($sql);
        $resultSet = $stmt->executeQuery();

        return $resultSet->fetchAllAssociative();
    }
}
