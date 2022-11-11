<?php

namespace BotBoris\Event;

use BotBoris\Habr\Article\Article;

class CodingDay extends Event
{
    public function isConditionCompleted(): bool
    {
        return $this->isItTheDay()
            && $this->wasTheEventToday();
    }

    public function getMessage(): string
    {
        return $this->getBestHabrWeeklyCodingArticle()?->getLink()
            ?? "Привет! Сегодня четверг - день программирования.";
    }

    private function isItTheDay(): bool
    {
        $date = new \DateTime();
        return $date->format('L') === 'thursday';
    }

    private function wasTheEventToday(): bool
    {
        return boolval($this->getStorage()->get(self::class));
    }

    private function getBestHabrWeeklyCodingArticle(): ?Article
    {
        $habr = new \BotBoris\Habr\Client\Http();
        return $habr->getBestArticle($habr::TIME_WEEK, $habr::TAG_CODING);
    }
}
