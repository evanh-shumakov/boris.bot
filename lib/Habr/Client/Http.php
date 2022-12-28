<?php

namespace BotBoris\Habr\Client;

use BotBoris\Habr\Article\Article;
use Symfony\Component\DomCrawler\Crawler;

class Http implements Client
{
    const PROTOCOL = 'https://';

    const DOMAIN = 'habr.com';

    const TOP_TAG_ENDPOINT_TEMPLATE = '/ru/hub/%s/top/%s/';

    public function getBestArticleByTag(string $time, string $tag): ?Article
    {
        $endpoint = sprintf(self::TOP_TAG_ENDPOINT_TEMPLATE, $tag, $time);
        $url = self::PROTOCOL . self::DOMAIN . $endpoint;
        $page = $this->getContents($url);
        if (! $page) {
            print "No page\n";
            return null;
        }
        $dom = new Crawler($page);
        $nodes = $dom->filter('.tm-article-snippet');
        return $this->makeArticle($nodes->first());
    }

    public function getContents(string $link): false|string
    {
        try {
            return file_get_contents($link);
        } catch (\Throwable) {
            return false;
        }
    }

    private function makeArticle(Crawler $snippet): Article
    {
        $href = $snippet->filter('.tm-article-snippet__title-link')
            ->attr('href');
        $link = self::PROTOCOL . self::DOMAIN . $href;
        $article = new Article();
        $article->setLink($link);
        return $article;
    }
}
