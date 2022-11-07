<?php

namespace BotBoris\Storage;

use BotBoris\StorageClient\StorageClient;
use BotBoris\Collection\UniqueStringCollection;

interface Storage
{
    public function getToken(): string;

    public function setToken(string $token): void;

    public function addChatId(string $chatId): void;

    public function getChatIds(): UniqueStringCollection;
}
