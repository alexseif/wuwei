<?php

namespace App\Controller\Api;

use App\Repository\TimeSystemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TimeSystemController extends AbstractController
{
    #[Route('/api/v1/temporal-blocks', name: 'api_v1_temporal_blocks', methods: ['GET'])]
    public function getTemporalBlocks(Request $request, TimeSystemRepository $timeSystemRepository): Response
    {
        $timeString = null;
        $timeParam = $request->query->get('time');

        if ($timeParam !== null) {
            $d = \DateTime::createFromFormat('H:i:s', $timeParam);
            if ($d && $d->format('H:i:s') === $timeParam) {
                $timeString = $timeParam;
            } else {
                $d = \DateTime::createFromFormat('H:i', $timeParam);
                if ($d && $d->format('H:i') === $timeParam) {
                    $timeString = $d->format('H:i:s');
                } else {
                    return $this->json([
                        'error' => 'Invalid time format. Must match H:i:s or H:i.'
                    ], Response::HTTP_BAD_REQUEST);
                }
            }
        }

        $blocks = $timeSystemRepository->getActiveForTime($timeString);

        $result = [];
        foreach ($blocks as $block) {
            $tagsGrouped = [];
            foreach ($block->getTags() as $tag) {
                $tagType = $tag->getTagType();
                $typeName = $tagType ? $tagType->getName() : 'Untyped';
                $tagsGrouped[$typeName][] = $tag->getName();
            }

            $result[] = [
                'id' => $block->getId(),
                'name' => $block->getName(),
                'from_time' => $block->getFromTime() ? $block->getFromTime()->format('H:i:s') : null,
                'to_time' => $block->getToTime() ? $block->getToTime()->format('H:i:s') : null,
                'tags' => $tagsGrouped,
            ];
        }

        return $this->json($result);
    }
}
