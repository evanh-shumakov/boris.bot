<?php

namespace BotBoris\Habr\Client;

use BotBoris\Habr\Article\Article;

interface Client
{
    const TIME_WEEK = 'weekly';

    const TAG_CODING = 'программирование';

    public function getBestArticle(string $time, string $tagContains): ?Article;
}
