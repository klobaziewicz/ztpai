<?php

require __DIR__.'/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('task_queue', false, true, false, false);

echo "[*] Czekam na wiadomości. Aby przerwać naciśnij CTRL+C\n";

$callback = function ($msg) {
    echo "[x] Otrzymano: ", $msg->body, "\n";
    sleep(substr_count($msg->body, '.')); // symulacja ciężkiego zadania
    echo "[v] Zakończono przetwarzanie\n";
    $msg->ack();
};

$channel->basic_qos(null, 1, null);
$channel->basic_consume('task_queue', '', false, false, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}