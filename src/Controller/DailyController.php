<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/daily', name: 'app_daily_')]
class DailyController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('daily/index.html.twig', [
          'controller_name' => 'DailyController',
        ]);
    }

    #[Route('/show', name: 'show')]
    public function showDaily(): Response
    {
        $dailySchedule = $this->generateDailySchedule();

        //        $dailyContent = file_get_contents(__DIR__ . '/../../docs/daily.md');
        //        dump($dailyContent);
        return $this->render('daily/show.html.twig', [
          'schedule' => $dailySchedule,
        ]);
    }

    private function generateDailySchedule(): array
    {
        return [
          'Morning' => [
            '6:00 am - 7:00 am' => 'Chinese: Lung energy peak - Deep breathing exercises or meditation',
            '7:00 am - 8:00 am' => 'Ayurveda: Kapha period - Gentle stretching or light exercise',
            '8:00 am - 9:00 am' => 'Egyptian: Time of the Sun - Sun salutations or energizing activities',
          ],
          'Work Focus (6 hours)' => [
            '9:00 am - 11:00 am' => 'Chinese: Spleen/Pancreas energy peak - Tackle important tasks requiring focus',
            '11:00 am - 11:30 am' => 'Ayurveda: Pitta period - Take a short break and enjoy a refreshing drink',
            '11:30 am - 1:00 pm' => 'Egyptian: Mercury period - Engage in creative work or brainstorming sessions',
            '1:00 pm - 2:00 pm' => 'Chinese: Stomach energy peak - Enjoy a balanced and nourishing lunch',
            '2:00 pm - 4:00 pm' => 'Ayurveda: Vata period - Collaborate with colleagues or engage in strategic planning',
            '4:00 pm - 4:30 pm' => 'Egyptian: Venus period - Socialize, network, or connect with others',
            '4:30 pm - 6:00 pm' => 'Chinese: Bladder energy peak - Complete remaining tasks and tie up loose ends',
          ],
          'Afternoon and Evening' => [
            '6:00 pm - 7:00 pm' => 'Ayurveda: Pitta period - Engage in physical exercise or enjoy a hobby',
            '7:00 pm - 8:00 pm' => 'Egyptian: Time of the Moon - Have a leisurely dinner and unwind',
            '8:00 pm - 9:00 pm' => 'Chinese: Pericardium energy peak - Spend quality time with loved ones or engage in self-care',
            '9:00 pm - 10:00 pm' => 'Ayurveda: Vata period - Wind down with relaxation techniques or gentle yoga',
            '10:00 pm - 6:00 am' => 'Egyptian: Time of Rest - Ensure a restful sleep for rejuvenation',
          ],
        ];
    }

}
