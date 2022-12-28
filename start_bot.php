<?php
if (php_sapi_name() !== 'cli') { // only run from command line
    exit();
}

require_once __DIR__ . '/vendor/autoload.php';
try {
    BotBoris\Bot::run();
} catch (Throwable $e) {
    print($e->getMessage() . "\n");
    print_r($e->getTrace());
    print("\n");
}
