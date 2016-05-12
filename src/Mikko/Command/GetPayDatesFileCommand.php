<?php

namespace Mikko\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Base\Base;

/**
 * command file to create PayDates file
 *
 * @output CSV file name generated
 *
 * @author Merlijn Cuypers <merlijn.cuypers@gmail.com>
 */
class GetPayDatesFileCommand extends Command
{

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * Configure the GetPayDatesFileCommand command
     */
    protected function configure()
    {
        $this->setName('mikko:getPayDates')
                ->setDescription('Get the dates to pay salaries to the sales department for this year.')
                ->addArgument('year', InputArgument::OPTIONAL, 'Set a future year to get the dates from', date('Y'));
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->io = new SymfonyStyle($this->input, $this->output);

        $base = new Base();
        // register execute to the log
        $base->log->info('GetPayDatesFileCommand executed');

        // run applictaion
        try {
            $paymentDates = $base->getController("Mikko\PaymentDates\Controller\PaymentDatesController");
            $fileName = $paymentDates->createCSVForYear($this->input->getArgument('year'));
            $this->io->success('The CSV file was created and can be found at docs/' . $fileName);
        } catch (\Exception $ex) {
            $base->log->error($ex->getMessage());
            throw $ex;
        }
    }



}
