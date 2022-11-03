<?php
require_once './../../security/bot_tokens.php';
require_once __DIR__ . '/vendor/autoload.php';

use Zanzara\Zanzara;
use Zanzara\Context;


$bot = new Zanzara(BORIS_DEV_TOKEN);
$bot->onCommand('start', fn (Context $ctx) => $ctx->sendMessage('Hello'));
$bot->run();
