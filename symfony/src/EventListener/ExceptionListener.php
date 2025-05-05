<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'kernel.exception')]
class ExceptionListener
{
public function __invoke(ExceptionEvent $event)
{
$exception = $event->getThrowable();

$statusCode = $exception instanceof HttpExceptionInterface
? $exception->getStatusCode()
: 500;

$response = new JsonResponse([
'success' => false,
'message' => $exception->getMessage(),
], $statusCode);

$event->setResponse($response);
}
}