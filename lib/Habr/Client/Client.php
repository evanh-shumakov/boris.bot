<?php

namespace BotBoris\Habr\Client;

use BotBoris\Habr\Article\Article;

interface Client
{
    const TIME_WEEK = 'weekly';

    const TAG_PROGRAMMING = 'programming';

    public function getBestArticleByTag(string $time, string $tags): ?Article;
}
