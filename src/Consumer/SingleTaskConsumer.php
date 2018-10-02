<?php
namespace App\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class SingleTaskConsumer implements ConsumerInterface
{

    // ./bin/console rabbitmq:consumer single_task
    public function execute(AMQPMessage $msg)
    {
        echo date('Y-m-d H:i:s');
        echo "    ";
        echo "ROUTING KEY: " . $msg->delivery_info['routing_key'] . PHP_EOL;
        dump($msg->body);

        echo "\n";
        sleep(2);
    }
}