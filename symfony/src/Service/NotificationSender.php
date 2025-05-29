<?php
namespace App\Service;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class NotificationSender
{
    public function send(string $message): void
    {
        //$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('notifications', false, true, false, false);

        $msg = new AMQPMessage($message, [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
        ]);

        $channel->basic_publish($msg, '', 'notifications');

        $channel->close();
        $connection->close();
    }
}