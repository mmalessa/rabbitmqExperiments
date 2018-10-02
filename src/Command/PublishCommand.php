<?php
namespace App\Command;

use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PublishCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        // ./bin/console rabbit:publish
        $this
            ->setName('rabbit:publish')
            ->setDescription('Publish to rabbit')
            ->setHelp('This command allows you to create a user...')
            ->addOption('routing-key', null, InputOption::VALUE_OPTIONAL, 'Optional routing key', 'test.task_job_done')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $msg = [
            'my_test_id' => rand(1,100000),
            'my_send_time' => date('Y-m-d H:i:s'),
        ];

        $routingKey = $input->getOption('routing-key');

        $this->getContainer()->get('old_sound_rabbit_mq.my_task_producer')->publish(serialize($msg), $routingKey);
    }


}