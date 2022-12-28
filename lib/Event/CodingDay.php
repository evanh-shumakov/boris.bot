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
        return true;
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
        $lastExecutionTimeStamp = $this->getStorage()->get(self::class);
        if (! $lastExecutionTimeStamp) {
            return false;
        }
        $lastExecution = new \DateTime();
        $lastExecution->setTimestamp($lastExecutionTimeStamp);
        $lastExecutionDayNumber = $lastExecution->format('Y-m-d');
        $today = new \DateTime();
        $todayDayNumber = $today->format('Y-m-d');
        return $lastExecutionDayNumber === $todayDayNumber;
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
