<?php

namespace App\Service;

class ParseService
{
    public string $date;
    public mixed $day_time = null;
    public static array $ru_months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
    public static array $en_months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    public static array $en_abbr_months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    public function __construct(string $date)
    {
        $this->date = $date;
    }

    public static function toRussian(\DateTimeInterface $schedule)
    {
        $date = date("F jS, Y", strtotime(((array)($schedule))['date']));

        $ru_date = str_replace(self::$en_months, self::$ru_months, $date);
        $ru_date = str_replace('nd', '', $ru_date);
        $ru_date = str_replace('st', '', $ru_date);
        $ru_date = str_replace('rd', '', $ru_date);
        return str_replace('th', '', $ru_date);
    }

    public function parseToDatetime()
    {
        $date = $this->date;
        if ($date == '') return $date;

        $date = str_replace(' ', '', $date);
        $year = substr($date, strpos($date, ',') + 1, 4);
        $month_name = substr($date, 0, 3);
        $month = array_search($month_name, self::$en_abbr_months) + 1;
        $day = substr($date, 3, strpos($date, ',') - 3);
        $time = (int)substr($date, strpos($date, ',') + 5, 2);
        $day_time = substr($date, strlen($date) - 2, 2);

        $time = $day_time == 'PM'
            ? $time + 12
            : $time;

        $this->day_time = $time;

        $time = $time == 24
            ? 0
            : $time;
        $month = strlen($month) == 2
            ? $month
            : '0' . $month;
        $day = strlen($day) == 2
            ? $day
            : '0' . $day;

        return "$year-$month-$day $time:00:00";
    }

    public function getDayTime()
    {
        return $this->day_time;
    }
}