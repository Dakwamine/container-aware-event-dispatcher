<?php

namespace Dakwamine\Event;

use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * Extends the PSR-14 listener provider for extra methods.
 */
interface ContainerAwareListenerProviderInterface extends ListenerProviderInterface
{
    /**
     * Adds a listener to the list of listeners.
     *
     * @param string $eventName
     *   The event name.
     * @param $listenerClassName
     *   The listener class name to use when fetching from the container.
     * @param int $priority
     *   The priority. Lower values are called first.
     */
    public function addListener($eventName, $listenerClassName, $priority = 0);
}
