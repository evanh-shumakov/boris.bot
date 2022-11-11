<?php

namespace BotBoris\Event;

use BotBoris\Collection\EventCollection;
use BotBoris\Filesystem\Filesystem;
use BotBoris\Storage\Storage;

use League\Flysystem\StorageAttributes;

use Zanzara\Zanzara;

abstract class Event
{
    private Zanzara $client;

    private Storage $storage;

    public static function getAllEvents(): EventCollection
    {
        $fullName = explode('\\', self::class);
        $abstractName = array_pop($fullName);

        $filesystem = new Filesystem(__DIR__);
        $items = $filesystem->listContents('');
        $fileExtension = '.php';
        $events = new EventCollection();
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
                $event = new $fullClassName();
                $events->add($event);
            }
        }

        return $events;
    }

    public function setStorage(Storage $storage): void
    {
        $this->storage = $storage;
    }

    public function setClient(Zanzara $client): void
    {
        $this->client = $client;
    }

    public function getStorage(): Storage
    {
        return $this->storage;
    }

    public function getClient(): Zanzara
    {
        return $this->client;
    }

    public function execute(): void
    {
        $telegram = $this->getClient()->getTelegram();
        foreach ($this->getStorage()->getChatIds() as $chatId) {
            $telegram->sendMessage($this->getMessage(), ['chat_id' => $chatId]);
        }
        $this->getStorage()->set(static::class, time(), 60 * 60 * 24);
    }

    abstract public function isConditionCompleted(): bool;

    abstract public function getMessage(): string;
}
