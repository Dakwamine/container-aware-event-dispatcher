<?php

namespace Dakwamine\Event;

use Psr\Container\ContainerInterface;

/**
 * Listener provider compatible with PSR containers.
 */
class ContainerAwareListenerProvider implements ContainerAwareListenerProviderInterface
{
    /**
     * Container used for dependency injection.
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * Listener groups. They contain listeners grouped by the same event.
     *
     * @var ListenerGroupInterface[]
     */
    private $listenerGroups = [];

    /**
     * ContainerAwareListenerRegistry constructor.
     *
     * @param ContainerInterface $container
     *   The container object which holds event listeners definitions.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Adds a listener.
     *
     * @param string $eventName
     *   Event name.
     * @param string $listenerClassName
     *   The listener class name, which will be used for container fetch.
     * @param int $priority
     *   The priority of this listener for the given event name.
     */
    public function addListener($eventName, $listenerClassName, $priority = 0)
    {
        if (empty($eventName) || !is_string($eventName)) {
            // Do not work with it.
            return;
        }

        if (empty($this->listenerGroups[$eventName])) {
            // Create the listener group.
            $this->listenerGroups[$eventName] = new ListenerGroup();
        }

        $this->listenerGroups[$eventName]->addListener($listenerClassName, $priority);
    }

    /**
     * {@inheritdoc}
     */
    public function getListenersForEvent(object $event): iterable
    {
        if (!$event instanceof EventInterface) {
            // Not an event one can handle.
            return [];
        }

        if (empty($this->listenerGroups[$event->getName()])) {
            // No listener for this event.
            return [];
        }

        foreach ($this->listenerGroups[$event->getName()]->getListeners() as $listenerClassName) {
            // Retrieve the listener instance.
            $instance = $this->container->get($listenerClassName);

            yield $instance;
        }
    }
}
