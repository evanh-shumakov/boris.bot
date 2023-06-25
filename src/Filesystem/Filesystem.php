<?php

namespace BotBoris\Filesystem;

use League\Flysystem\DirectoryListing;
use League\Flysystem\FilesystemException;
use League\Flysystem\Local\LocalFilesystemAdapter;

class Filesystem
{
    private \League\Flysystem\Filesystem $client;

    public function __construct(string $path)
    {
        $adapter = new LocalFilesystemAdapter($path);
        $this->client = new \League\Flysystem\Filesystem($adapter);
    }

    public function listContents(string $path): DirectoryListing
    {
        try {
            $items = $this->client->listContents($path);
        } catch (FilesystemException $e) {
            //todo: handle exception
            return new DirectoryListing([]);
        }
        return $items;
    }
}
