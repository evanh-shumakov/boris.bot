<?php

namespace BotBoris\Registry;

use BotBoris\Collection\UniqueStringCollection;
use BotBoris\Storage\Storage;

class Registry
{
    private Storage $storage;

    private string $token;

    private UniqueStringCollection $chatIds;

    public static function init(Storage $storage): self
    {
        $instance = new self();
        $instance->fillPropertiesFromStorage($storage);
        return $instance;
    }

    private function __construct()
    {
        /** there is nothing here */
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
        $this->storage->setToken($token);
    }

    public function addChatId(string $chatId): void
    {
        if ($this->chatIds->has($chatId)) {
            return;
        }
        $this->chatIds->add($chatId);
        $this->storage->addChatId($chatId);
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getChatIds(): UniqueStringCollection
    {
        return $this->chatIds;
    }

    public function getStorage(): Storage
    {
        return $this->storage;
    }

    private function fillPropertiesFromStorage(Storage $storage): void
    {
        $this->storage = $storage;
        $this->token = $this->storage->getToken();
        $this->chatIds = $this->storage->getChatIds();
    }
}
