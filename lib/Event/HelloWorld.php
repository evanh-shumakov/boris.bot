<?php

namespace BotBoris\Event;

class HelloWorld extends Event
{
    public function isConditionCompleted(): bool
    {
        return true;
    }

    public function getMessage(): string
    {
        return 'Hello, world!';
    }
}
