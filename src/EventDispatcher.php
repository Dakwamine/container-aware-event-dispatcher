<?php

namespace Dakwamine\Event;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

/**
 * The event dispatcher.
 */
class EventDispatcher implements EventDispatcherInterface
{
    /**
     * The listener provider holding the list of listeners.
     *
     * @var ListenerProviderInterface
     */
    private $listenerProvider;

    /**
     * EventDispatcher constructor.
     *
     * @param ListenerProviderInterface $listenerProvider
     *   The listener provider holding the list of listeners.
     */
    public function __construct(ListenerProviderInterface $listenerProvider)
    {
        $this->listenerProvider = $listenerProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(object $event)
    {
        if (!$event instanceof EventInterface) {
            // Not an event one can handle.
            return $event;
        }

        $isStoppable = $event instanceof StoppableEventInterface;

        foreach ($this->listenerProvider->getListenersForEvent($event) as $listener) {
            /** @var EventListenerInterface $listener */
            $listener->handleEvent($event);

            if (!$isStoppable) {
                continue;
            }

            /** @var StoppableEventInterface $event */
            if ($event->isPropagationStopped()) {
                break;
            }
        }

        return $event;
    }
}
