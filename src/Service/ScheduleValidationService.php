<?php

namespace App\Service;

use App\Entity\Repository\ScheduleRepository;
use App\Exception\ValidationException;
use DateTime;
use DateTimeZone;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ScheduleValidationService
{
    private bool $failed = false;
    private array $data = [];
    private ScheduleRepository $repository;

    public function __construct(array $data, ScheduleRepository $repository)
    {
        $this->data = $data;
        $this->repository = $repository;
    }

    public function validated()
    {
        $this->validateDate();
        $this->validateUsers();
        $this->validateSchedulePersistance();

        if ($this->isFailed()) {
            throw new ValidationException(new RedirectResponse('/schedules/create'));
        }

        return $this->data;
    }

    private function validateDate()
    {
        $will_at = $this->data['will_at'];
        if (gettype($will_at) != 'string') {
            $_SESSION['message'] = 'Укажите дату в формате строки';
            $this->setFailed();
        }
        $parse_service = new ParseService($will_at);
        $will_at = $parse_service->parseToDatetime();
        $day_time = $parse_service->getDayTime();
        if($day_time < 9 or $day_time > 18) {
            $_SESSION['message'] = 'Укажите время с 9 утра до 18 вечера';
            $this->setFailed();
        }
        $now = new DateTime('', new DateTimeZone('Europe/Moscow'));

        if ($will_at == '') {
            $_SESSION['message'] = 'Укажите дату';
            $this->setFailed();
        }
        $will_at = new DateTime($will_at, new DateTimeZone('Europe/Moscow'));
        if ($will_at < $now) {
            $_SESSION['message'] = 'Укажите дату в будущем времени';
            $this->setFailed();
        }

        $this->data['will_at'] = $will_at;
    }

    private function validateUsers()
    {
        if (!$this->data['student']) {
            $_SESSION['message'] = 'Такого ученика нет';
            $this->setFailed();
        }
        if (!$this->data['teacher']) {
            $_SESSION['message'] = 'Такого учителя нет';
            $this->setFailed();
        }
    }

    private function validateSchedulePersistance()
    {
        $schedule = $this->repository->findBy($this->data);
        if ($schedule) {
            $_SESSION['message'] = 'На это время уже запланировано занятие';
            $this->setFailed();
        }
    }

    public function isFailed()
    {
        return $this->failed;
    }

    private function setFailed()
    {
        $this->failed = true;
    }
}