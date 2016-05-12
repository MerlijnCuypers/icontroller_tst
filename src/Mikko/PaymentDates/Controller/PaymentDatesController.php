<?php

namespace Mikko\PaymentDates\Controller;

use Base\Controller\BaseController;

/**
 * As only controller of the application it is overkill to work with actions
 * is only used to get MVC like flow in application
 * and seperate the input validation from logic tier (service layer)
 *
 * @author Merlijn Cuypers <merlijn.cuypers@gmail.com>
 */
class PaymentDatesController extends BaseController
{

    /**
     * validate input and call logic tier
     * @param int $year
     */
    public function createCSVForYear($year)
    {
        //validate year argument
        $this->validateYear($year);

        if($this->service->createCSVForYear($year)) {
            return $this->service->fileName;
        }
    }

    /**
     * check if argument year is vallid
     */
    private function validateYear($year)
    {
        switch (true) {
            case!is_numeric($year):
                throw new \Exception('The given argument as year is not a vallid numeric value');
            case (int) $year < date('Y'):
                throw new \Exception('The given argument as year is not a vallid futur year');
            case!checkdate(01, 01, $year):
                throw new \Exception('The given argument as year is not allowed to be bigger than 32767');
        }
    }

}
