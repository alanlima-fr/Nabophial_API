<?php

declare(strict_types=1);

namespace App\Listener;

use App\Exception\NabofialExceptionInterface;
use App\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof NabofialExceptionInterface) {
            return;
        }

        $statusCode = Response::HTTP_BAD_REQUEST;
        if ($exception instanceof NotFoundException) {
            $statusCode = Response::HTTP_NOT_FOUND;
        }

        $event->setResponse(new JsonResponse(['error' => $exception->getMessage()], $statusCode));
    }
}
