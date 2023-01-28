<?php

namespace BotBoris\Event;

use BotBoris\Habr\Article\Article;

class CodingDay extends Event
{
    public function isConditionCompleted(): bool
    {
        return $this->isItTheDay()
            && $this->isItTheTime()
            && ! $this->wasTheEventToday();
    }

    public function getMessage(): string
    {
        return $this->getBestHabrWeeklyCodingArticle()?->getLink()
            ?? "Привет! Сегодня четверг - день программирования.";
    }

    private function isItTheDay(): bool
    {
        $date = new \DateTime();
        return $date->format('l') === 'Thursday';
    }

    private function isItTheTime(): bool
    {
        $date = new \DateTime();
        $hours = intval($date->format('G'));
        return $hours >= 7;
    }

    private function wasTheEventToday(): bool
    {
        $lastExecution = $this->getLastExecution();
        if (is_null($lastExecution)) {
            return false;
        }
        return $lastExecution->diff(new \DateTime())->h < 24;
    }

    private function getBestHabrWeeklyCodingArticle(): ?Article
    {
        $habr = new \BotBoris\Habr\Client\Http();
        return $habr->getBestArticleByTag(
            $habr::TIME_WEEK,
            $habr::TAG_PROGRAMMING
        );
    }
}
