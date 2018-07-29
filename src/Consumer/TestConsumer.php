<?php
namespace App\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class TestConsumer implements ConsumerInterface
{

    public function execute(AMQPMessage $msg)
    {
        echo date('Y-m-d H:i:s');
        echo "    ";
        print_r($msg->body);
        echo "\n";
        sleep(2);
    }
}