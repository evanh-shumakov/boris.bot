<?php

namespace BotBoris\Storage;

use BotBoris\Collection\UniqueStringCollection;

class MemcacheStorage implements Storage
{
    private \Memcache $client;

    public function __construct()
    {
        $this->client = new \Memcache();
        $host = 'unix:///home/i37394/.memcached/memcached.sock';
        $port = 0;
        $this->client->connect($host, $port);
    }

    public function getToken(): string
    {
        return $this->client->get('token');
    }

    public function setToken(string $token): void
    {
        $this->client->set('token', $token);
    }

    public function getChatIds(): UniqueStringCollection
    {
        $ids = $this->client->get('chat_ids');
        if (! $ids) {
            return new UniqueStringCollection();
        }
        return new UniqueStringCollection($ids);
    }

    public function addChatId(string $chatId): void
    {
        $ids = $this->getChatIds();
        if (! $ids->has($chatId)) {
            $ids->add($chatId);
            $this->client->set('chat_ids', $ids->toArray());
        }
    }

    public function set(string $key, $value, int $expire): void
    {
        $this->client->set($key, $value, 0, $expire);
    }

    /**
     * @param string $key
     * @return array|string|false
     *
     * Returns FALSE on failure, key is not found or key is an empty array.
     */
    public function get(string $key): array|string|false
    {
        return $this->client->get($key);
    }
}
