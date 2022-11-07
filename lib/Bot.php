<?php

namespace BotBoris;

use BotBoris\Event\Event;
use BotBoris\Storage\Storage;
use BotBoris\Storage\MemcacheStorage;

use Zanzara\Context;
use Zanzara\Zanzara;

class Bot
{
    private Zanzara $client;

    private string $token;

    private Storage $storage;

    public static function run(): void
    {
        $boris = new self();
        $boris->requireToken();
        $boris->chargeEvents();
        $boris->chargeChatIdListener();
        $boris->getClient()->run();
    }

    private function chargeEvents(): void
    {
        $client = $this->getClient();
        $events = Event::getAllEvents();
        $events->setBotClient($client);
        $events->setStorage($this->getStorage());
        $client->getLoop()->addPeriodicTimer(
            60,
            fn() => $events->executeAppropriate()
        );
    }

    private function chargeChatIdListener(): void
    {
        $this->getClient()->onUpdate(function (Context $ctx) {
            $chatId = $ctx->getUpdate()?->getEffectiveChat()?->getId();
            if ($chatId) {
                $this->getStorage()->addChatId($chatId);
            }
        });
    }

    private function requireToken(): void
    {
        $token = $this->getStorage()->getToken();
        if (! $token) {
            $token = readline("Enter bot token: ");
            $this->getStorage()->setToken($token);
        }
        $this->setToken($token);
    }

    private function setToken(string $token): void
    {
        $this->token = $token;
    }

    private function getToken(): string
    {
        return $this->token;
    }

    private function getClient(): Zanzara
    {
        return $this->client ??= new Zanzara($this->getToken());
    }

    private function getStorage(): Storage
    {
        return $this->storage ??= new MemcacheStorage();
    }
}
