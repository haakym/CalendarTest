<?php

namespace Calendar;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class Calendar implements CalendarInterface
{
    /**
     * @param DateTimeInterface $datetime
     */
    public function __construct(DateTimeInterface $datetime)
    {
        $this->datetime = DateTimeImmutable::createFromMutable($datetime);
    }

    /**
     * Get the day
     *
     * @return int
     */
    public function getDay()
    {
        return (int) $this->datetime->format('j');
    }

    /**
     * Get the month
     *
     * @return int
     */
    public function getMonth()
    {
        return (int) $this->datetime->format('n');
    }

    /**
     * Get the weekday (1-7, 1 = Monday)
     *
     * @return int
     */
    public function getWeekDay()
    {
        return (int) $this->datetime->format('N');
    }

    /**
     * Get the first weekday of this month (1-7, 1 = Monday)
     *
     * @return int
     */
    public function getFirstWeekDay()
    {
        $days = $this->getDay() - 1;

        return (int) $this->datetime->sub(new DateInterval("P{$days}D"))->format('N');
    }

    /**
     * Get the first week of this month (18th March => 9 because March starts on week 9)
     *
     * @return int
     */
    public function getFirstWeek()
    {
        $days = $this->getDay() - 1;

        $this->datetime->sub(new DateInterval("P{$days}D"));

        return $this->datetime->format('W');
    }

    /**
     * Get the number of days in this month
     *
     * @return int
     */
    public function getNumberOfDaysInThisMonth()
    {
        return (int) $this->datetime->format('t');
    }

    /**
     * Get the number of days in the previous month
     *
     * @return int
     */
    public function getNumberOfDaysInPreviousMonth()
    {
        $this->datetime->sub(new DateInterval("P1M"));

        return $this->getNumberOfDaysInThisMonth();
    }

    /**
     * Get the calendar array
     *
     * @return array
     */
    public function getCalendar()
    {
        // build calendar object in array
        $calendar = [];
        $weeks = [];

        $daysToSubtract = $this->getWeekDay() - 1;

        $date = (new DateTime($this->datetime->format('Y-m-d')))->sub(new DateInterval("P{$daysToSubtract}D"));

        while ($date->format('m') === $this->datetime->format('m')) {
            $date->sub(new DateInterval("P7D"));
        }

        $buildCalendar = true;

        $weekToHighlight = null;

        while ($buildCalendar) {
            $week = $date->format('W');
            $weeks[] = $week;

            for ($i = 0; $i < 7; $i++) {
                $calendar[(int) $week][(int) $date->format('d')] = false;

                if ($date->format('Y-m-d') === $this->datetime->format('Y-m-d') && count($calendar) !== 1) {
                    $weekToHighlight = key(array_slice($weeks, -2, 1, true));
                    var_dump('$weekToHighlight');
                    var_dump($weekToHighlight);
                }

                $date->add(new DateInterval("P1D"));
            }

            if ($date->format('Y-m-d') >= $this->datetime->format('Y') . '-' . $this->datetime->format('m') . '-' . $this->getNumberOfDaysInThisMonth()) {
                $buildCalendar = false;
            }
        }

        if ($weekToHighlight !== null) {
            foreach ($calendar[array_keys($calendar)[$weekToHighlight]] as $key => $value) {
                $calendar[array_keys($calendar)[$weekToHighlight]][$key] = true;
            }
        }

        var_dump( 'POOOOOOOOOOOOOOOOOOOOOOOOO' );
        var_dump( $calendar );
        // var_dump( $date );

        return $calendar;
    }
}
