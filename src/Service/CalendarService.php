<?php

namespace App\Service;

class CalendarService
{



    public function getCalendarEvents($tasks): array
    {
        $events = [];
        $currentTime = new \DateTime('09:00:00'); // Start time is 9 AM
        $currentTime = new \DateTime(); 

        foreach ($tasks as $task) {
            $start = $task->getEta() ?? $currentTime;
            $duration = $task->getEst() ?? 60; // Default duration is 60 minutes
            $end = (clone $start)->modify("+$duration minutes");

            // Move to the next available time slot
            $currentTime = (clone $end)->modify("+10 minutes");

            // Ensure tasks fit within the 9 AM - 6 PM window
            if ($start->format('H:i') >= '09:00' && $end->format('H:i') <= '18:00') {
                $events[] = [
                    'id' => $task->getId(),
                    'title' => $task->getTask(),
                    'start' => $start->format('Y-m-d\TH:i:s'),
                    'end' => $end->format('Y-m-d\TH:i:s'),
                ];
            }
        }

        return $events;
    }
}
