<?php

namespace BotBoris;

use BotBoris\Event\Event;
use BotBoris\Registry\Registry;

use Psr\Log\LoggerInterface;
use Zanzara\Context;
use Zanzara\Zanzara;
use Zanzara\Config;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Bot
{
    private Zanzara $client;

    private string $token;

    private Registry $registry;

    public static function run(Registry $registry): void
    {
        $boris = new self();
        $boris->setRegistry($registry);
        $boris->requireToken();
        $boris->chargeEvents();
        $boris->chargeChatIdListener();
        $boris->getClient()->run();
    }

    private function __construct()
    {
        /** there is nothing here */
    }

    private function chargeEvents(): void
    {
        $client = $this->getClient();
        $events = Event::getAllEvents($client, $this->getRegistry());
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
                $this->getRegistry()->addChatId($chatId);
            }
        });
    }

    private function requireToken(): void
    {
        $registry = $this->getRegistry();
        $token = $registry->getToken();
        if (! $token) {
            $token = readline("Enter bot token: ");
            $registry->setToken($token);
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
        return $this->client
            ??= new Zanzara($this->getToken(), $this->getConfig());
    }

    private function setRegistry(Registry $registry): void
    {
        $this->registry = $registry;
    }

    private function getRegistry(): Registry
    {
        return $this->registry;
    }

    private function getConfig(): Config
    {
        $logger = $this->getLogger();
        $config = new Config();
        $config->setLogger($logger);
        return $config;
    }

    private function getLogger(): LoggerInterface
    {
        $today = date('Y-m-d');
        $file = __DIR__ . "/../log/$today.log";
        $handler = new StreamHandler($file, Logger::DEBUG);
        $logger = new Logger('boris');
        $logger->pushHandler($handler);
        return $logger;
    }
}
