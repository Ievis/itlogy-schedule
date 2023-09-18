<?php

namespace App\Service;

class MainService
{
    public static function parseAllDates(array $schedules): array
    {
        return array_map(function ($schedule) {
            $schedule['will_at'] = ParseService::toRussian($schedule['will_at']);

            return $schedule;
        }, $schedules);
    }
}