<?php

namespace App\Service;

use Doctrine\ORM\EntityRepository;

class PaginationService
{
    private EntityRepository $repository;
    private array $entities;
    private int $page;
    private int $per_page;
    private array $links;
    private array $urls;

    public function __construct(EntityRepository $repository, $page, $per_page)
    {
        $this->repository = $repository;
        $this->page =  max($page, 1);;
        $this->per_page = $per_page;
    }

    public function paginate()
    {
        $this->entities = $this->repository->paginateWithUsers($this->page);
        $count = $this->repository->countAll();
        $last = round($count / 10) + 1;
        $next = $this->page + 1;
        $prev = $this->page - 1;
        if ($this->page <= 1) {
            $prev = null;
        }
        if ($this->page >= $last) {
            $next = null;
            $prev = $last - 1;
        }

        $this->links = [
            'last' => (string)$last,
            'prev' => (string)$prev,
            'next' => (string)$next
        ];

        $this->urls = [
            'last' => "/?page=$last",
            'prev' => "/?page=$prev",
            'next' => "/?page=$next"
        ];
    }

    public function getEntities()
    {
        return $this->entities;
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function getUrls()
    {
        return $this->urls;
    }
}