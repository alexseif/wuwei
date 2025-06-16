<?php

namespace App\Service;

class CalendarService
{

    //TODO: set variables for start and end time of workday

    public function getCalendarEvents(array $tasks, string $startTime = '09:00', string $endTime = '18:00'): array
    {
        $events = [];
        $now = new \DateTime(); // Current time
        $currentDay = new \DateTime(); // Tracks the scheduling day
        $currentTime = clone $now; // Default to current time

        // Ensure scheduling starts within work hours
        $workStartToday = (clone $currentDay)->setTime((int)explode(':', $startTime)[0], (int)explode(':', $startTime)[1]);
        $workEndToday = (clone $currentDay)->setTime((int)explode(':', $endTime)[0], (int)explode(':', $endTime)[1]);

        // If login is before working hours, start at 9 AM; if after, push to the next available workday
        if ($currentTime < $workStartToday) {
            $currentTime = clone $workStartToday;
        } elseif ($currentTime > $workEndToday) {
            $currentDay->modify('+1 day');
            $currentTime = (clone $currentDay)->setTime((int)explode(':', $startTime)[0], (int)explode(':', $startTime)[1]);
        }

        foreach ($tasks as $task) {
            if ($task->getEta() !== null) {
                $events[] = [
                    'id'    => $task->getId(),
                    'title' => $task->getTask(),
                    'start' => $task->getEta()->format('Y-m-d\TH:i:s'),
                    'end'   => $task->getEta()->modify('+' . ($task->getEst() ?? 60) . ' minutes')->format('Y-m-d\TH:i:s'),
                    'url'   => '/tasks/' . $task->getId() . '/edit?returnUrl=/schedule/calendar',
                ];
                // If the task already has an ETA, skip it

                continue;
            }
            $duration = $task->getEst() ?? 60; // Default duration is 60 minutes
            $endTimeObj = (clone $currentDay)->setTime((int)explode(':', $endTime)[0], (int)explode(':', $endTime)[1]);

            // **Ensure we don't schedule tasks on Friday or Saturday**
            while (in_array($currentDay->format('N'), [5, 6])) { // 5 = Friday, 6 = Saturday
                $currentDay->modify('+1 day');
                $currentTime = (clone $currentDay)->setTime((int)explode(':', $startTime)[0], (int)explode(':', $startTime)[1]);
            }

            // Ensure tasks fit within working hours
            if (($currentTime->getTimestamp() + ($duration * 60)) > $endTimeObj->getTimestamp()) {
                // Move to the next available workday at 9 AM
                $currentDay->modify('+1 day');

                // **Ensure the new workday isn't a weekend**
                while (in_array($currentDay->format('N'), [5, 6])) {
                    $currentDay->modify('+1 day');
                }

                $currentTime = (clone $currentDay)->setTime((int)explode(':', $startTime)[0], (int)explode(':', $startTime)[1]);
            }

            $start = clone $currentTime;
            $end = (clone $start)->modify("+$duration minutes");

            // Move to the next available slot with buffer
            $currentTime = (clone $end)->modify("+10 minutes");

            $events[] = [
                'id'    => $task->getId(),
                'title' => $task->getTask(),
                'start' => $start->format('Y-m-d\TH:i:s'),
                'end'   => $end->format('Y-m-d\TH:i:s'),
                'url'   => '/tasks/' . $task->getId() . '/edit?returnUrl=/schedule/calendar',

            ];
        }

        return $events;
    }
}
