<?php

namespace BotBoris\Storage;

use BotBoris\Collection\UniqueStringCollection;

interface Storage
{
    public function setToken(string $token): void;

    public function getToken(): string;

    public function addChatId(string $chatId): void;

    public function getChatIds(): UniqueStringCollection;
}
