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
//        dump($msg->delivery_info);

        echo "\n";
        sleep(2);

        return true;
        return false;

        return ConsumerInterface::MSG_SINGLE_NACK_REQUEUE;  // (??)
        return ConsumerInterface::MSG_ACK;                  // Remove message from queue only if callback return not false
        return ConsumerInterface::MSG_REJECT_REQUEUE;       // Reject and requeue message to RabbitMQ
        return ConsumerInterface::MSG_REJECT;               // Reject and drop
        return ConsumerInterface::MSG_ACK_SENT;             // ack not sent by the consumer but should be sent by the implementer of ConsumerInterface (??)
        
    }
}