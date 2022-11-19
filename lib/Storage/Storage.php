<?php

namespace BotBoris\Storage;

use BotBoris\Collection\UniqueStringCollection;

interface Storage
{
    public function set(string $key, array|string $value, int $expire): void;

    /**
     * @param string $key
     * @return array|string|false
     *
     * Returns FALSE on failure, key is not found or key is an empty array.
     */
    public function get(string $key): array|string|false;

    public function getToken(): string;

    public function setToken(string $token): void;

    public function addChatId(string $chatId): void;

    public function getChatIds(): UniqueStringCollection;
}
