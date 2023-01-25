<?php

if (php_sapi_name() !== 'cli') { // only run from command line
    exit();
}

require_once __DIR__ . '/vendor/autoload.php';

BotBoris\Bot::run();
