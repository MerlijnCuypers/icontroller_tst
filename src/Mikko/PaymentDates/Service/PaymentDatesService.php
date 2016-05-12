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

    /**
     * validate input and call logic tier
     * @param int $year
     */
    public function createCSVForYear($year)
    {
        // setup year obj with remaining months

        // setup salery days and bonus days

        // export data to file

        // set fileName
        $this->fileName = 'jeej good one';
        return true;
    }

}
