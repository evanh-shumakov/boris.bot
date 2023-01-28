<?php

namespace BotBoris\Event;

use BotBoris\Collection\EventCollection;
use BotBoris\Filesystem\Filesystem;
use BotBoris\Registry\Registry;

use League\Flysystem\StorageAttributes;

use Zanzara\Zanzara;

abstract class Event
{
    private Zanzara $client;

    private Registry $registry;

    private \DateTime $lastExecution;

    public static function getAllEvents(
        Zanzara $client,
        Registry $registry
    ): EventCollection {
        $fullName = explode('\\', self::class);
        $abstractName = array_pop($fullName);

        $filesystem = new Filesystem(__DIR__);
        $items = $filesystem->listContents('');
        $fileExtension = '.php';
        $events = new EventCollection($client, $registry);
        /** @var StorageAttributes $item */
        foreach ($items as $item) {
            $name = $item->path();
            if (! str_ends_with($name, $fileExtension)) {
                continue;
            }
            $extension = "/\\" . $fileExtension . "$/";
            $className = preg_replace($extension, "", $name);
            $fullClassName = __NAMESPACE__ . '\\' . $className;
            if (class_exists($fullClassName) && $className !== $abstractName) {
                /** @var self $event */
                $event = new $fullClassName();
                $events->add($event);
            }
        }

        return $events;
    }

    public function setRegistry(Registry $registry): void
    {
        $this->registry = $registry;
    }

    public function setClient(Zanzara $client): void
    {
        $this->client = $client;
    }

    public function getRegistry(): Registry
    {
        return $this->registry;
    }

    public function getClient(): Zanzara
    {
        return $this->client;
    }

    public function execute(): void
    {
        $telegram = $this->getClient()->getTelegram();
        foreach ($this->getRegistry()->getChatIds() as $chatId) {
            $telegram->sendMessage($this->getMessage(), ['chat_id' => $chatId]);
        }
        $this->updateLastExecution();
    }

    public function getLastExecution():? \DateTime
    {
        return $this->lastExecution ?? null;
    }

    private function updateLastExecution(): void
    {
        $this->lastExecution = new \DateTime();
    }

    abstract public function isConditionCompleted(): bool;

    abstract public function getMessage(): string;
}
