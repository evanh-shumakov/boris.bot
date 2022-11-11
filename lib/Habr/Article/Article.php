<?php

namespace BotBoris\Habr\Article;

class Article
{
    private string $link;

    public function getLink(): string
    {
        return $this->link;
    }

    public function setLink(string $link): void
    {
        $this->link = $link;
    }
}