<?php

namespace BotBoris\Collection;

class UniqueStringCollection implements \Iterator
{
    /** @var string[] */
    private array $collection = [];

    private int $position = 0;

    /**
     * @param string[] $strings
     * @throws \InvalidArgumentException
     */
    public function __construct(array $strings = [])
    {
        $uniqueStrings = array_unique($strings);
        foreach ($uniqueStrings as $string) {
            if (! is_string($string)) {
                throw new \InvalidArgumentException('Key must be a string');
            }
        }
        $this->collection = array_values($uniqueStrings);
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

    public function toArray(): array
    {
        return $this->collection;
    }

    public function has(string $chatId): bool
    {
        return in_array($chatId, $this->collection);
    }

    public function current(): string
    {
        return $this->collection[$this->position];
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->collection[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }
}
