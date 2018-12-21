<?php
namespace App\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Container\ContainerInterface;

class AllLogsDeadConsumer implements ConsumerInterface
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    // ./bin/console rabbitmq:consumer all_logs_dead
    public function execute(AMQPMessage $msg)
    {
//        echo date('Y-m-d H:i:s');
//        echo "    ";
//        echo "ROUTING KEY: " . $msg->delivery_info['routing_key'] . PHP_EOL;
//        dump($msg->body);

        $body = unserialize($msg->body);
//        printf("%04s\t%020s\t%01s\n", $body['id'], $body['send_time'], $body['priority']);

        dump($body);
//        print_r($body);
//        print json_encode($msg->body) . PHP_EOL;

//        echo "DeliveryTag: " . $msg->delivery_info['delivery_tag'] . PHP_EOL;
//
//        $testparameter = $this->container->getParameter('testparameter');
//        echo "TestParameter: $testparameter" . PHP_EOL;
//        echo "Redelivered: " . ($msg->delivery_info['redelivered'] ? 'Y' : 'N') . PHP_EOL;


//        try {
//            dump($msg->get('application_headers')->getNativeData()['x-death']);
//        } catch (\Exception $e) {
//            echo "No 'application_headers' -> OK" . PHP_EOL;
//        }

//        echo PHP_EOL;
        sleep(1);

        //$msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag'], false, false);


//        return true;
//        return false;

//        return ConsumerInterface::MSG_SINGLE_NACK_REQUEUE;  // (??)
        return ConsumerInterface::MSG_ACK;                  // Remove message from queue only if callback return not false
//        return ConsumerInterface::MSG_REJECT_REQUEUE;       // Reject and requeue message to RabbitMQ
//        return ConsumerInterface::MSG_REJECT;               // Reject and drop (if x-dead-letter-exchange is configured -> re-send to it)
//        return ConsumerInterface::MSG_ACK_SENT;             // ack not sent by the consumer but should be sent by the implementer of ConsumerInterface (??)

    }
}