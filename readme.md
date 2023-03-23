# BorisBot
BorisBot is a Telegram bot built with ReactPHP that helps to amuse
users in a Telegram chat. It checks all events, and post
messages for those events where the conditions are met.
## Event
To add a new event, follow these steps:
1. Create a new class in the `BorisBot\Event` namespace.
2. Implement the `BorisBot\Event\Event` interface in your new class.

Here's an example of how your class might look:

```php
<?php

namespace BorisBot\Event;

class NewDayCongratulater implements Event
{
    public function isConditionCompleted(): bool
    {
        return $this->wasTheEventToday();
    }

    public function getMessage(): string
    {
        return 'Happy new day!';
    }

    public function wasTheEventToday(): bool
    {
        $lastExecution = $this->getLastExecution(); // BorisBot\Event\Event::getLastExecution()
        if (is_null($lastExecution)) {
            return false;
        }
        return $lastExecution->diff(new \DateTime())->h < 24;
    }
}
```
## Development
To test new features, you need to register a new bot in Telegram's BotFather
and get a token. Once you have the token, run the bot 
(for example, with [Docker](#docker)). When you start the bot it ask you for the
token. Enter the token and the bot will start working. Add bot to any chat where
you want to test it and send any message, so it can register the chat ID.
## Docker
### build
- `$ make build`
### XDebug (PHPStorm)
- uncomment XDebug section in Dockerfile and rebuild the image
- File -> Settings -> PHP:
    - CLI Interpreter -> ... -> Add -> From Docker -> select `boris-bot` image
    - CLI Interpreter -> Docker container:
        - Volume bindings:
            - Container path: `/app`
        - Port bindings:
            - Container port: 8080
            - Host port: 8080
    - Servers -> Add:
        - Name: `docker`
        - Host: `docker`
        - Port: `80`
        - Absolute path on server (for project root): `/app`
- Set breakpoints in PHPStorm before starting the bot (those breakpoints will be
  ignored if you set them after the bot has started)
- Right click on `start_bot.php` -> Debug `start_bot.php` 
