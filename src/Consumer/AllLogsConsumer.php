<?php
namespace App\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class AllLogsConsumer implements ConsumerInterface
{

    // ./bin/console rabbitmq:consumer all_logs
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