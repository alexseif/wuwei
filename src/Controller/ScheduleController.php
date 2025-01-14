<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TasksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/schedule')]
class ScheduleController extends AbstractController
{
    #[Route('/', name: 'schedule_index')]
    public function index(TasksRepository $tasksRepository): Response
    {
        $tasks = $tasksRepository->getPaginatorResult(['t.completed' => false]);
        $schedule = $this->calculateSchedule($tasks);

        return $this->render('schedule/index.html.twig', [
            'schedule' => $schedule,
            'now' => new \DateTime()
        ]);
    }

    private function calculateSchedule(array $tasks): array
    {
        $now = new \DateTime();
        $endTime = new \DateTime('18:00'); // End time at 6pm
        $scheduleEnd = clone $now;
        $scheduleEnd
            ->modify('+3 days')
            ->setTime(18, 0);
        $breakTime = 10; // 10 minutes break
        $totalTime = 0;
        $schedule = [];
        $workBlocks = 0;
        $dayNumber = 0;
        foreach ($tasks as $task) {
            if ($task->isCompleted()) {
                $task->setEta($task->getCompletedAt()->modify('-' . $task->getDuration() . ' minutes'));
                // $totalTime += ($task->getDuration() + $breakTime);
                $schedule[$dayNumber][] = $task;
                continue;
            }
            dump($totalTime);
            if ($task->getEst() == null) {
                $task->setEst(60);
            }

            // $startDateTime = clone $startTime;
            $startDateTime = clone $now;
            $startDateTime->modify("+{$totalTime} minutes");

            $totalTime += $task->getEst();

            if (((int) $totalTime / 45) > $workBlocks) {
                $workBlocks = (int) $totalTime / 45;
                $totalTime += 10;
            }

            // Check if the task end time exceeds the end time
            $endDateTime = clone $startDateTime;
            $endDateTime->modify("+{$task->getEst()} minutes");

            if ($endDateTime > $endTime) {
                if ($endDateTime >= $scheduleEnd) {
                    break;
                }
                $endTime->modify('+1 days');
                $now
                    ->modify('+1 days')
                    ->setTime(9, 0);
                $startDateTime = clone $now;
                $dayNumber++;
                $workBlocks = 0;
                $endDateTime = clone $startDateTime;
                $endDateTime->modify("+{$task->getEst()} minutes");
                $totalTime = $task->getEst();
            }

            $task->setEta($startDateTime);
            $schedule[$dayNumber][] = $task;

            // Add break time after each work block
            $totalTime += $breakTime;
        }

        return $schedule;
    }
}
