<?php

namespace App\Twig;

use App\Repository\CigaretteLogRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CigaretteLogExtension extends AbstractExtension
{

    // Inject the CigaretteLogRepository to access the cigarette log data
    public function __construct(private readonly CigaretteLogRepository $cigaretteLogRepository)
    {
    }

    // Define the new Twig function
    public function getFunctions(): array
    {
        return [
          new TwigFunction(
            'cigarette_difference',
            $this->getCigaretteDifference(...)
          ),
        ];
    }

    // Calculate the difference in cigarette count between the current day and the previous day
    public function getCigaretteDifference(): int
    {
        $currentDayCount = $this->cigaretteLogRepository->countByCurrentDayAndTime(
        );
        $previousDayCount = $this->cigaretteLogRepository->countByPreviousDayAndTime(
        );
        return $currentDayCount['count'] - $previousDayCount['count'];
    }

}