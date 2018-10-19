<?php

/*
 * https://github.com/corvus-ch/rabbitmq-cli-consumer
 */

namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RabbitCliConsumerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        // ./bin/console rabbit:cli:consumer
        //rabbitmq-cli-consumer --verbose --url amqp://guest:guest@localhost --queue myqueue --executable command.php --include
        //rabbitmq-cli-consumer --verbose --url amqp://guest:guest@localhost --queue myqueue --executable 'bin/console rabbit:cli:consumer --env=prod' --include --strict-exit-code
        $this
            ->setName('rabbit:cli:consumer')
            ->addArgument('event', InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        $message = base64_decode($input->getArgument('event'));
        $message = json_decode(base64_decode($input)->getArgument('event'));
        //$this->getContainer()->get('mailer')->send($message);
        dump($message);

        exit(0);
    }
    /*
     * 0 	Acknowledgement
     * 3 	Reject
     * 4 	Reject and re-queue
     * 5 	Negative acknowledgement
     * 6 	Negative acknowledgement and re-queue
     */


}