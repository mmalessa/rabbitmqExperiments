<?php
namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PublishCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('rabbit:publish')
            ->setDescription('Publish to rabbit')
            ->setHelp('This command allows you to create a user...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $msg = [
            'my_test_id' => rand(1,100000),
            'my_send_time' => date('Y-m-d H:i:s'),
//            'my_secret_message' => 'Uuuu la la...',
        ];

        $this->getContainer()->get('old_sound_rabbit_mq.my_task_producer')->publish(serialize($msg));
    }
}