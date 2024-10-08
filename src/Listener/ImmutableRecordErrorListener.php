<?php

declare(strict_types=1);

namespace TeamBlue\Exception\Listener;

use EventEngine\Data\ImmutableRecord;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(
    event: KernelEvents::CONTROLLER_ARGUMENTS,
    priority: 3,
)]
class ImmutableRecordErrorListener
{
    public function __invoke(ControllerArgumentsEvent $event): void
    {
        $exception = $event->getRequest()->attributes->get('exception');

        if (! ($exception instanceof ImmutableRecord)) {
            return;
        }

        $event->stopPropagation();
    }
}
