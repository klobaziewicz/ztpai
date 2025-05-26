<?php
namespace App\Service;

use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class NotificationConsumer
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function consume(): void
    {
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        //$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('notifications', false, true, false, false);

        echo " [*] Czekam na powiadomienia. CTRL+C żeby przerwać\n";

        $callback = function (AMQPMessage $msg) {
            echo ' [x] Odebrano: ', $msg->body, "\n";

            $data = json_decode($msg->body, true);

            $notification = new Notification();
            $notification->setFromUser($data['from_user']);
            $notification->setToUser($data['to_user']);
            $notification->setPostId($data['post_id']);
            $notification->setCreatedAt((new \DateTime())->setTimestamp($data['timestamp']));

            $this->em->persist($notification);
            $this->em->flush();

            $msg->ack();
        };

        $channel->basic_consume('notifications', '', false, false, false, false, $callback);

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}
