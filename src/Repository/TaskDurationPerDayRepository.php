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
}
