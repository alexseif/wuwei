<?php

namespace App\Roadmap\Controller;

use App\Roadmap\Model\Roadmap;
use App\Roadmap\Model\Step;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Yaml\Yaml;

final class RoadmapController extends AbstractController
{
    #[Route('/', name: 'app_roadmap_index')]
    public function index(): Response
    {
        // Parse YAML file
        $data = Yaml::parseFile('/var/www/wuwei/src/Roadmap/config/roadmap.yaml');

        // Create Roadmap and set the title
        $roadmap = new Roadmap();
        $roadmap->title = $data['roadmap']['title'];

        // Add steps dynamically from the YAML file
        foreach ($data['roadmap']['steps'] as $stepData) {
            $roadmap->addStep(new Step(
                $stepData['title'],
                [
                    'start' => $stepData['start'] ?? false,
                    'end'   => $stepData['end'] ?? false,
                    'top'   => $stepData['top'] ?? null,
                    'left'  => $stepData['left'] ?? null,
                ]
            ));
        }

        // Render the roadmap with JSON data
        return $this->render('roadmap/roadmap.html.twig', [
            'roadmap' => $roadmap,
            'roadmapJson' => json_encode($roadmap->toArray()),
        ]);
    }

    #[Route('/save', name: 'roadmap_save', methods: ['POST'])]
    public function save(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['yaml'])) {
            return new JsonResponse(['message' => 'Invalid data'], 400);
        }

        try {
            // Save the YAML content to roadmap.yaml
            file_put_contents('/var/www/wuwei/src/Roadmap/config/roadmap.yaml', $data['yaml']);

            return new JsonResponse(['message' => 'Roadmap saved successfully']);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Failed to save roadmap'], 500);
        }
    }
}
