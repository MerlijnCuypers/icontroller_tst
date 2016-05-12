<?php

namespace Mikko\PaymentDates\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Base\Service\BaseService;

/**
 * Logic tier of application
 *
 *
 * @author Merlijn Cuypers <merlijn.cuypers@gmail.com>
 */
class PaymentMonthsService extends BaseService
{

    /**
     *
     * @var array;
     */
    protected $months;

    /**
     * keep DateTime Object with current date for memory management
     * @var \DateTime
     */
    private $now;

    public function __construct()
    {
        $this->months = [];
        $this->now = new \DateTime();
    }

    /**
     *
     * @param type $year
     * @return ArrayCollection
     */
    public function getDataForYear($year)
    {
        // generate data if data not known in object
        if (!array_key_exists($year, $this->months)) {
            $this->generateMonthsForYear($year);
        }
        return $this->months[$year];
    }

    /**
     * validate input and call logic tier
     * @param int $year
     */
    private function generateMonthsForYear($year)
    {
        // init months as fresh ArrayCollection;
        $this->months[$year] = new ArrayCollection();

        $initMonth = 1;
        // for this year set only remaining months
        if ($year == $this->now->format('Y')) {
            $initMonth = (int) $this->now->format('n');
        }

        for ($m = $initMonth; $m <= 12; $m++) {
            $cacheKey = $year . '_' . $m;
            // collect cache data
            $monthData = $this->cache->fetch($cacheKey);
            //check for months in cache and month and year are not current month and year
            if ($monthData && $cacheKey != $this->now->format('Y_n')) {
                $this->log->info('get cache ' . $cacheKey);
                $month = unserialize($monthData);
            } else {
                // generate data for new
                $month = $this->generateMonth($year, $m);
            }

            $this->months[$year]->add($month);
        }
    }

    /**
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    private function generateMonth($year, $month)
    {
        $cacheKey = $year . '_' . $month;
        $this->log->info('create cache ' . $cacheKey);

        // calculate data
        $saleryDate = $this->calculateSaleryDate($year, $month);
        $bonusDate = $this->calculateBonusDate($year, $month);

        // asemble data
        $monthData = array(
            "year" => $year,
            "monthId" => $month,
            "monthName" => date("F", mktime(0, 0, 0, $month, 1, $year)),
            "salaryDate" => $saleryDate,
            'bonusDate' => $bonusDate);
        // save data in cache
        $this->cache->save($cacheKey, serialize($monthData));
        return $monthData;
    }

    /**
     *
     * @param int $year
     * @param int $month
     * @return string date
     */
    private function calculateSaleryDate($year, $month)
    {
        $saleryDate = clone $this->now;
        $saleryDate->setDate($year, $month, '01')->modify('last day of this month');

        return $this->getFinalDate($saleryDate, 'previous friday');
    }

    /**
     *
     * @param int $year
     * @param int $month
     * @return string date
     */
    private function calculateBonusDate($year, $month)
    {
        $bonusDate = clone $this->now;
        $bonusDate->setDate($year, $month, '15')->modify('next month');

        return $this->getFinalDate($bonusDate, 'next Wednesday');
    }

    /**
     *
     * @param \DateTime $date
     * @param string $modForWeekend
     * @return string date
     */
    private function getFinalDate(\DateTime $date, $modForWeekend = null)
    {
        if ($modForWeekend && $date->format('N') >= 6) {
            $date->modify($modForWeekend);
        }

        // no dates allowed smaller than now
        if ($date < $this->now) {
            return '';
        }

        return $date->format('Y-m-d');
    }

}
