<?php
namespace App\Utilities;

use Carbon\Carbon;
use Carbon\CarbonInterval;

class TickerChartPeriods
{
    private static function getAppropriateDate(Carbon $date): int
    {
        return $date->isWeekend() ? $date->clone()->previousWeekday()->getTimestamp() : $date->clone()->getTimestamp();
    }

    private static function getDatesForPeriod(string $totalInterval, string $dividerInterval): array
    {
        $totalCarbon = new CarbonInterval($totalInterval);
        $dividerCarbon = new CarbonInterval($dividerInterval);

        $dynamicDate = new Carbon;
        $dynamicDate->setTime(0, 0);
        $startDate = new Carbon;
        $startDate->subtract($totalCarbon);
        $dates = array();

        while ($dynamicDate->getTimestamp() >= $startDate->getTimestamp()) {
            $dates[] = self::getAppropriateDate($dynamicDate);
            $dynamicDate->subtract($dividerCarbon);
        }

        $dates[] = self::getAppropriateDate($dynamicDate);

        return $dates;
    }

    public static function getAllDates(): array
    {
        $periods = config('ui.chart_periods');
        $allDates = array();
        foreach ($periods as $period) {
            $periodDates = self::getDatesForPeriod($period['total'], $period['divider']);
            $allDates = array_merge($allDates, $periodDates);
        }
        // remove duplicates
        $allDates = array_unique($allDates);
        rsort($allDates);
        return $allDates;
    }
}
