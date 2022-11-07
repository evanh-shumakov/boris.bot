<?php

namespace BotBoris\Collection;

class UniqueKeyCollection implements \Iterator
{
    private array $collection = [];

    private int $position = 0;

    public function __construct(array $keys = [], bool $shouldCheck = true)
    {
        if ($shouldCheck === true) {
            $keys = array_unique($keys);
        }
        $this->collection = array_values($keys);
    }

    public function add(string $item): void
    {
        if (! in_array($item, $this->collection)) {
            $this->collection[] = $item;
        }
    }

    public function count(): int
    {
        return count($this->collection);
    }

    public function current(): string
    {
        return $this->collection[$this->position];
    }

    public function next(): string
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

    public function rewind(): string
    {
        $this->position = 0;
        return $this->current();
    }
}
