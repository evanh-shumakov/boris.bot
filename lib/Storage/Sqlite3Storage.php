<?php

namespace BotBoris\Storage;

use BotBoris\Collection\UniqueStringCollection;

class Sqlite3Storage implements Storage
{
    private \SQLite3 $client;

    public function __construct()
    {
        $this->client = new \SQLite3(
            __DIR__ . '/../../db.sqlite3',
            SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE
        );
        $this->initTables();
    }

    public function setToken(string $token): void
    {
        $query = 'INSERT INTO parameter ("key", "value") VALUES ("token", :token)';
        $result = $this->execute($query, [':token' => $token]);
        if ($result === false) {
            throw new \Exception('Error while inserting token');
        }
    }

    public function getToken(): string
    {
        $query = 'SELECT value FROM parameter WHERE "key" = "token"';
        $result = $this->execute($query);
        if ($result === false) {
            throw new \Exception('Error while getting token');
        }
        $row = $result->fetchArray(SQLITE3_ASSOC);
        return $row['value'] ?? "";
    }

    public function addChatId(string $chatId): void
    {
        $query = 'INSERT INTO chat ("chat_id") VALUES (:chat_id)';
        $result = $this->execute($query, [':chat_id' => $chatId]);
        if ($result === false) {
            throw new \Exception('Error while inserting chat id');
        }
    }
    
    public function getChatIds(): UniqueStringCollection
    {
        $query = "SELECT chat_id FROM chat";
        $result = $this->execute($query);
        if ($result === false) {
            throw new \Exception('Error while getting chat ids');
        }
        $ids = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $ids[] = strval($row['chat_id']);
        }
        return new UniqueStringCollection($ids);
    }

    public function execute(
        string $query,
        array $values = []
    ): false|\SQLite3Result {
        $statement = $this->client->prepare($query);
        foreach ($values as $index => $value) {
            $statement->bindValue($index, $value);
        }
        return $statement->execute();
    }

    public function initTables(): void
    {
        $this->client->query('CREATE TABLE IF NOT EXISTS "parameter" (
            "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            "key" STRING,
            "value" STRING
        )');
        $this->client->query('CREATE TABLE IF NOT EXISTS "chat" (
            "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            "chat_id" STRING
        )');
    }
}
