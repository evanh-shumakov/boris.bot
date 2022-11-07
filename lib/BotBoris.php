<?php

namespace BotBoris;

use BotBoris\Event\Event;
use BotBoris\Storage;

use Zanzara\Context;
use Zanzara\Zanzara;

class BotBoris
{
    private Zanzara $client;

    private string $token;

    private Storage $storage;

    public static function run(): void
    {
        $boris = new self();
        $boris->checkToken();
        $boris->chargeEvents();
        $boris->chargeChatIdListener();
        $boris->getClient()->run();
    }

    private function getClient(): Zanzara
    {
        return $this->client ??= new Zanzara($this->getToken());
    }

    private function chargeEvents(): void
    {
        $client = $this->getClient();
        $events = Event::getAllEvents();
        $events->setBotClient($client);
        $events->executeAppropriate();
        $client->getLoop()->addPeriodicTimer(
            60,
            fn() => $events->executeAppropriate()
        );
    }

    private function chargeChatIdListener(): void
    {
        $client = $this->getClient();
        $client->onUpdate(function (Context $ctx) {
            $chatId = $ctx->getUpdate()?->getEffectiveChat()?->getId();
            $ctx->sendMessage('Chat ID: ' . $chatId);
        });
    }

    private function checkToken(): void
    {
        $token = $this->getStorage()->getToken();
        if (! $token) {
            $token = readline('Enter bot token: ');
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

    private function getStorage(): Storage
    {
        return $this->storage ??= new Storage();
    }
}
