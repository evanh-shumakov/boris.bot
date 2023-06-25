<?php

namespace BotBoris\Storage;

use BotBoris\Collection\UniqueStringCollection;

class MemcacheStorage implements Storage
{
    private \Memcache $client;

    private string $prefix;

    public function __construct()
    {
        $this->client = new \Memcache();
        $host = 'unix:///home/i37394/.memcached/memcached.sock';
        $port = 0;
        $this->client->connect($host, $port);
        $this->prefix = __DIR__ . '/';
    }

    public function getToken(): string
    {
        return $this->get('token');
    }

    public function setToken(string $token): void
    {
        $this->set('token', $token);
    }

    public function getChatIds(): UniqueStringCollection
    {
        $ids = $this->get('chat_ids');
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
            $this->set('chat_ids', $ids->toArray());
        }
    }

    public function set(string $key, $value, int $expire = null): void
    {
        $this->client->set($this->prefix . $key, $value, 0, $expire);
    }

    /**
     * @param string $key
     * @return array|string|false
     *
     * Returns FALSE on failure, key is not found or key is an empty array.
     */
    public function get(string $key): array|string|false
    {
        return $this->client->get($this->prefix . $key);
    }

    public function delete(string $key): void
    {
        $this->client->delete($this->prefix . $key);
    }
}
