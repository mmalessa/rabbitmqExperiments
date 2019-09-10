<?php
namespace App\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use Psr\Container\ContainerInterface;

class AllLogsConsumer implements ConsumerInterface
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    // ./bin/console rabbitmq:consumer all_logs
    public function execute(AMQPMessage $msg)
    {
        echo "all_logs\n";

        // BODY
        sprintf("Body size: %s\n", $msg->body_size);
        sprintf("Is truncated: %s\n", $msg->is_truncated);
        sprintf("Content encoding: %s\n", $msg->content_encoding);
        $body = json_decode($msg->body);
        echo "BODY:\n";
        dump($body);
        echo PHP_EOL;

        // PROPERTIES
        echo "PROPERTIES:\n";
        dump($msg->get_properties());
        printf("Content type: %s\n", $msg->get('content_type'));
        printf("Delivery mode: %s\n", $msg->get('delivery_mode'));
        printf("Priority: %s\n", $msg->get('priority'));
        echo PHP_EOL;

        // DELIVERY INFO
        echo "DELIVERY INFO:\n";
        dump(array_keys($msg->delivery_info));
        printf("Consumer tag: %s\n", $msg->get('consumer_tag'));
        printf("Delivery tag: %s\n", $msg->get('delivery_tag'));
        printf("Redelivered: %s\n", $msg->get('redelivered'));
        printf("Exchange name: %s\n", $msg->get('exchange'));
        printf("Routing key: %s\n", $msg->get('routing_key'));
        echo PHP_EOL;

        /** @var AMQPChannel $channelInfo */
        $channelInfo = $msg->get('channel');

        try {
            /** @var AMQPTable $applicationHeaders */
//            echo "APPLICATION HEADERS\n";
            $applicationHeaders = $msg->get('application_headers');
//            dump($applicationHeaders);
//            echo PHP_EOL;

            echo "APPLICATION HEADERS NATIVE DATA\n";
            $applicationHeadersNativeData = $applicationHeaders->getNativeData();
            dump($applicationHeadersNativeData);
            echo PHP_EOL;

        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }

        sleep(1);



//        return true; // == MSG_ACK
//        return false; // == MSG_REJECT

//        return ConsumerInterface::MSG_SINGLE_NACK_REQUEUE;  // (??)
//        return ConsumerInterface::MSG_ACK;                  // Remove message from queue only if callback return not false
//        return ConsumerInterface::MSG_REJECT_REQUEUE;       // Reject and requeue message to RabbitMQ
        return ConsumerInterface::MSG_REJECT;               // Reject and drop (if x-dead-letter-exchange is configured -> re-send to it)
//        return ConsumerInterface::MSG_ACK_SENT;             // ack not sent by the consumer but should be sent by the implementer of ConsumerInterface (??)

    }
}
