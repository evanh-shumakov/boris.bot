<?php

if (php_sapi_name() !== 'cli') { // only run from command line
    exit();
}

require_once __DIR__ . '/vendor/autoload.php';

$storage = new \BotBoris\Storage\Sqlite3Storage();
$registry = \BotBoris\Registry\Registry::init($storage);
BotBoris\Bot::run($registry);
