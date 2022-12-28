<?php

namespace BotBoris\Collection;

use BotBoris\Event\Event;
use BotBoris\Storage\Storage;

use Zanzara\Zanzara;

class EventCollection implements \Iterator
{
    /** @var Event[] */
    private array $collection = [];

    private int $position = 0;

    public function setBotClient(Zanzara $bot): void
    {
        foreach ($this->collection as $event) {
            $event->setClient($bot);
        }
    }

    public function executeAppropriate(): void
    {
        foreach ($this->collection as $event) {
            if ($event->isConditionCompleted()) {
                $event->execute();
            }
        }
    }

    public function add(Event $event): void
    {
        $this->collection[] = $event;
    }

    public function count(): int
    {
        return count($this->collection);
    }

    public function current(): Event
    {
        return $this->collection[$this->position];
    }

    public function next(): Event
    {
        ++$this->position;
        return $this->current();
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->collection[$this->position]);
    }

    public function rewind(): Event
    {
        $this->position = 0;
        return $this->current();
    }

    public function setStorage(Storage $storage): void
    {
        foreach ($this->collection as $event) {
            $event->setStorage($storage);
        }
    }
}
