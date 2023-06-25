<?php

namespace BotBoris\Event;

class HelloWorld extends Event
{
    public function isConditionCompleted(): bool
    {
        return false;
    }

    public function getMessage(): string
    {
        return 'Hello, world!';
    }
}
