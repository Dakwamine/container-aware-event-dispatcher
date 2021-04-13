<?php


namespace Dakwamine\Event;

/**
 * Contains the listeners responding to the same event.
 */
class ListenerGroup implements ListenerGroupInterface
{
    /**
     * Array of event listeners class names nested in priority keys.
     *
     * E.g.:
     * [-15 => ['EventA', 'EventB'], 0 => ['EventX'], 20 => ['EventY']]
     *
     * @var array
     */
    private $listeners = [];

    /**
     * Tells if the group needs sorting.
     *
     * @var bool
     */
    private $needsSorting = false;

    /**
     * {@inheritdoc}
     */
    public function addListener($eventListenerClassName, $priority = 0)
    {
        $this->listeners[$priority][] = $eventListenerClassName;
        $this->needsSorting = true;
    }

    /**
     * {@inheritdoc}
     */
    public function getListeners()
    {
        if ($this->needsSorting) {
            $this->sortListeners();
        }

        $output = [];

        foreach ($this->listeners as $priority => $listeners) {
            foreach ($listeners as $listener) {
                $output[] = $listener;
            }
        }

        return $output;
    }

    /**
     * Call this when listeners must be sorted by priority.
     *
     * Do not call this too often.
     */
    private function sortListeners()
    {
        ksort($this->listeners, SORT_NUMERIC);
        $this->needsSorting = false;
    }
}
