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

        return ConsumerInterface::MSG_SINGLE_NACK_REQUEUE;  // 2
        return ConsumerInterface::MSG_ACK;                  // 1
        return ConsumerInterface::MSG_REJECT_REQUEUE;       // 0
        return ConsumerInterface::MSG_REJECT;               // -1
        return ConsumerInterface::MSG_ACK_SENT;             // -2

/*
        array(null, 'basic_ack'), // Remove message from queue only if callback return not false
        array(true, 'basic_ack'), // Remove message from queue only if callback return not false
        array(false, 'basic_reject', true), // Reject and requeue message to RabbitMQ
        array(ConsumerInterface::MSG_ACK, 'basic_ack'), // Remove message from queue only if callback return not false
        array(ConsumerInterface::MSG_REJECT_REQUEUE, 'basic_reject', true), // Reject and requeue message to RabbitMQ
        array(ConsumerInterface::MSG_REJECT, 'basic_reject', false), // Reject and drop
        array(ConsumerInterface::MSG_ACK_SENT), // ack not sent by the consumer but should be sent by the implementer of ConsumerInterface
*/

    }
}