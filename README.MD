# Container aware event dispatcher

A simple event dispatcher which works with `"psr/container": "^2.0"` and `"psr/event-dispatcher": "^1.0"`.

## Basic usage

```php
<?php

class SomeEvent implements \Dakwamine\Event\EventInterface
{
  const EVENT_NAME = 'Any string';

  public function getName(){
    return static::EVENT_NAME;
  }
}

class SomeEventListener implements \Dakwamine\Event\EventListenerInterface
{
  public function handleEvent(\Dakwamine\Event\EventInterface $event) {
    // Do stuff related to $event, like dispatching to other methods.
    if ($event->getName() === SomeEvent::EVENT_NAME) {
      // ...
    }
  }
}

// The container.
$container = new \League\Container\Container();

// Add the listener to the container.
$container->share(SomeEventListener::class);

// This object holds the lists of class names per event.
$listenerProvider = new \Dakwamine\Event\ContainerAwareListenerProvider($container);

// Register the listener. Optionally set the priority.
$listenerProvider->addListener(SomeEvent::EVENT_NAME, SomeEventListener::class, 42);

// Dispatch the event. Listeners will be instantiated using the container by the listener provider.
$eventDispatcher = new \Dakwamine\Event\EventDispatcher($listenerProvider);
$eventDispatcher->dispatch(new SomeEvent());
```