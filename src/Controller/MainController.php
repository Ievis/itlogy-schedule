<?php

namespace App\Controller;

use App\Service\MainService;
use App\Service\PaginationService;
use App\View\View;
use PDO;

class MainController extends AbstractController
{
    public function index(PDO $pdo)
    {
        dd($pdo);
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