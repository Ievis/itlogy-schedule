<?php

namespace App\Controller;

use App\Entity\Repository\ScheduleRepository;
use App\Service\MainService;
use App\Service\PaginationService;
use App\View\View;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    public function index(Request $request, ScheduleRepository $repository)
    {
        $page = (int)$request->query->get('page') ?? 1;
        $pagination = new PaginationService($repository, $page, 10);
        $pagination->paginate();
        $schedules = $pagination->getEntities();
        $links = $pagination->getLinks();
        $urls = $pagination->getUrls();
        $schedules = MainService::parseAllDates($schedules);

        return new View('main', [
            'schedules' => $schedules,
            'links' => $links,
            'urls' => $urls
        ]);
    }
}