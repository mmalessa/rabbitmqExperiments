<?php
namespace App\Command;

//use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
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

        for ($i=0; $i<30; $i++) {

            $priority = ($i < 15 ? 0 : 3);

            if ($i < 10) $priority = 0;
            elseif ($i < 20) $priority = 1;
            else $priority = 2;
            
            $msg = [
//                'my_test_id' => rand(1, 100000),
                'id' => $i,
                'send_time' => date('Y-m-d H:i:s'),
                'priority' => $priority,
            ];
            $routingKey = $input->getOption('routing-key');
            $properties = [
                'priority' => $priority
            ];
            $this->getContainer()->get('old_sound_rabbit_mq.my_task_producer')->publish(serialize($msg), $routingKey, $properties);
        }
    }


}