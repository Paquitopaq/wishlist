<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EventController extends AbstractController
{
    #[Route('/events', name: 'events')]
    public function index(Request $request): Response
    {
        $city = ucfirst(strtolower($request->query->get('city', 'Niort')));
        $date = $request->query->get('date', date('Y-m-d'));

        $url = "https://public.opendatasoft.com/api/records/1.0/search/?" .
            "dataset=evenements-publics-openagenda" .
            "&refine.location_city=" . urlencode($city) .
            "&refine.firstdate_begin=" . urlencode($date);

        $response = file_get_contents($url);
        $data = json_decode($response, true);

        return $this->render('event/index.html.twig', [
            'events' => $data['records'] ?? [],
            'city' => $city,
            'date' => $date,
        ]);
    }
}
