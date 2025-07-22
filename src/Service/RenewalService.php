<?php

namespace App\Service;

use App\Entity\AccountServiceAssignment;
use Doctrine\ORM\EntityManagerInterface;
use Recurr\Rule;
use Recurr\Transformer\ArrayTransformer;

class RenewalService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Get upcoming renewal dates for an assignment
     *
     * @param AccountServiceAssignment $assignment
     * @param int $limit Maximum number of upcoming dates to return
     * @param \DateTimeInterface|null $until Only return dates until this date
     * @return array Array of \DateTime objects representing upcoming renewal dates
     */
    public function getUpcomingRenewalDates(AccountServiceAssignment $assignment, int $limit = 5, \DateTimeInterface $until = null): array
    {
        if (!$assignment->getRrule() || !$assignment->getRenewalDate()) {
            return [];
        }

        $rule = new Rule($assignment->getRrule(), $assignment->getRenewalDate());

        if ($until) {
            $rule->setUntil($until);
        }

        $transformer = new ArrayTransformer();
        $recurrences = $transformer->transform($rule)->slice(0, $limit);

        $dates = [];
        foreach ($recurrences as $recurrence) {
            $dates[] = $recurrence->getStart();
        }

        return $dates;
    }

    /**
     * Get the next renewal date for an assignment
     *
     * @param AccountServiceAssignment $assignment
     * @return \DateTimeInterface|null The next renewal date or null if no recurrence rule is set
     */
    public function getNextRenewalDate(AccountServiceAssignment $assignment): ?\DateTimeInterface
    {
        $dates = $this->getUpcomingRenewalDates($assignment, 1);
        return $dates[0] ?? null;
    }

    /**
     * Check if an assignment is overdue
     *
     * @param AccountServiceAssignment $assignment
     * @return bool True if the assignment is overdue, false otherwise
     */
    public function isOverdue(AccountServiceAssignment $assignment): bool
    {
        if ($assignment->isComplete()) {
            return false;
        }

        $nextDate = $this->getNextRenewalDate($assignment);
        if (!$nextDate) {
            return false;
        }

        return $nextDate < new \DateTime();
    }

    /**
     * Get all assignments with upcoming renewals
     *
     * @param int $days Number of days to look ahead
     * @return array Array of AccountServiceAssignment objects with upcoming renewals
     */
    public function getUpcomingRenewals(int $days = 30): array
    {
        $repository = $this->entityManager->getRepository(AccountServiceAssignment::class);
        $assignments = $repository->findBy(['isComplete' => false]);

        $now = new \DateTime();
        $until = (new \DateTime())->modify("+{$days} days");

        $upcomingRenewals = [];

        foreach ($assignments as $assignment) {
            $dates = $this->getUpcomingRenewalDates($assignment, 5, $until);

            foreach ($dates as $date) {
                if ($date >= $now && $date <= $until) {
                    $upcomingRenewals[] = [
                        'assignment' => $assignment,
                        'renewalDate' => $date
                    ];
                    break; // Only include the first upcoming renewal for each assignment
                }
            }
        }

        // Sort by renewal date
        usort($upcomingRenewals, function($a, $b) {
            return $a['renewalDate'] <=> $b['renewalDate'];
        });

        return $upcomingRenewals;
    }

    /**
     * Get all overdue assignments
     *
     * @return array Array of AccountServiceAssignment objects that are overdue
     */
    public function getOverdueRenewals(): array
    {
        $repository = $this->entityManager->getRepository(AccountServiceAssignment::class);
        $assignments = $repository->findBy(['isComplete' => false]);

        $overdueRenewals = [];

        foreach ($assignments as $assignment) {
            if ($this->isOverdue($assignment)) {
                $overdueRenewals[] = [
                    'assignment' => $assignment,
                    'renewalDate' => $this->getNextRenewalDate($assignment)
                ];
            }
        }

        // Sort by renewal date (oldest first)
        usort($overdueRenewals, function($a, $b) {
            return $a['renewalDate'] <=> $b['renewalDate'];
        });

        return $overdueRenewals;
    }
}