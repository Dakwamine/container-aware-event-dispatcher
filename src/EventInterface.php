<?php

namespace Dakwamine\Event;

/**
 * Interface for events.
 */
interface EventInterface
{
    /**
     * Returns the event name.
     *
     * @return string
     *   The event name.
     */
    public function getName();
}
