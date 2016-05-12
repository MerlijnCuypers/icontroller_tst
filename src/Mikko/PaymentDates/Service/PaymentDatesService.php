<?php

namespace Mikko\PaymentDates\Service;

use Base\Service\BaseService;

/**
 * Logic tier of application
 *
 *
 * @author Merlijn Cuypers <merlijn.cuypers@gmail.com>
 */
class PaymentDatesService extends BaseService
{

    /**
     * the fileName of the csv that is created
     * @var string
     */
    protected $fileName;
    protected $csvFile;

    /**
     * get the data and put it in a file
     *
     * @param int $year
     */
    public function createCSVForYear($year)
    {
        $paymentMonthsService = $this->callService('Mikko\PaymentDates\Service\PaymentMonthsService');
        // collect data
        $data = $paymentMonthsService->getDataForYear($year);

        // create file and dir
        $dir = __DIR__ . '/../../../../docs/';
        $this->fileName = $year . '_' . date("Ymd_His").'.csv';
        // handle dir
        if (!is_dir($dir)) {
            $status = mkdir($dir, 0775, true);
            if (false === $status) {
                throw new Exception(sprintf('Not able do create dir "%s"', $dir));
            }
        }
        // handle file
        $this->csvFile = fopen($dir . $this->fileName , 'w');
        // File couldn't be opened
        if ($this->csvFile === false) {
            throw new Exception(sprintf("The file \"%s\" couldn't be opened ", $this->fileName));
        }
        // set data in file
        $titles = array('Month', 'Salary pay date', 'Bonus pay date');
        fputcsv($this->csvFile, $titles, ';');
        $data->map(function($month) {
            fputcsv($this->csvFile, [$month['monthName'], $month['salaryDate'], $month['bonusDate']], ';');
        });

        return true;
    }

}
