<?php

namespace App\Command;

use App\Service\NotificationConsumer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

if (!defined('PhpAmqpLib\Wire\IO\SOCKET_EAGAIN')) {
    define('PhpAmqpLib\Wire\IO\SOCKET_EAGAIN', 10035);
}

if (!defined('PhpAmqpLib\Wire\IO\SOCKET_EWOULDBLOCK')) {
    define('PhpAmqpLib\Wire\IO\SOCKET_EWOULDBLOCK', 10035);
}
if (!defined('PhpAmqpLib\Wire\IO\SOCKET_EINTR')) {
    define('PhpAmqpLib\Wire\IO\SOCKET_EINTR', 10004);
}
#[AsCommand(name: 'app:consume-notifications')]
class ConsumeNotificationsCommand extends Command
{
    private NotificationConsumer $consumer;

    public function __construct(NotificationConsumer $consumer)
    {
        parent::__construct();
        $this->consumer = $consumer;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->consumer->consume();
        return Command::SUCCESS;
    }
}
