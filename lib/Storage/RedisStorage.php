<?php

namespace BotBoris\Storage;

use BotBoris\Collection\UniqueStringCollection;

class Memchach implements Storage
{
    public function __construct()
    {
        $this->redis = new \Redis();
        $this->redis->connect('redis', 6379);
    }

    
    public function getToken(): string
    {
        // TODO: Implement getToken() method.
    }

    public function setToken(string $token): void
    {
        // TODO: Implement setToken() method.
    }

    public function addChatId(string $chatId): void
    {
        // TODO: Implement addChatId() method.
    }

    public function getChatIds(): UniqueStringCollection
    {
        // TODO: Implement getChatIds() method.
    }
}
