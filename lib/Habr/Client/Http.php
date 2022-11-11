<?php

namespace BotBoris\Habr\Client;

use BotBoris\Habr\Article\Article;
use Symfony\Component\DomCrawler\Crawler;

class Http implements Client
{
    const PROTOCOL = 'https://';

    const DOMAIN = 'habr.com';

    const TOP_NEWS_ENDPOINT_TEMPLATE = '/ru/news/top/';

    public function getBestArticle(string $time, string $tagContains): ?Article
    {
        $url = self::PROTOCOL
            . self::DOMAIN
            . self::TOP_NEWS_ENDPOINT_TEMPLATE
            . $time
            . '/';
        $pageNumber = 1;
        while ($page = file_get_contents($url)) {
            $dom = new Crawler($page);
            $article = $this->getTagArticleFromPage($dom, $tagContains);
            if (! is_null($article)) {
                return $article;
            }
            $url = $url . "page" . ++$pageNumber . "/";
        }
        return null;
    }

    private function getTagArticleFromPage(
        Crawler $page,
        string $tagContains
    ): ?Article
    {
        $nodes = $page->filter('.tm-article-snippet');
        foreach ($nodes as $node) {
            $snippet = new Crawler($node);
            $tagLinks = $snippet->filter(
                '.tm-article-snippet__hubs-item-link'
            );
            foreach ($tagLinks as $tagLink) {
                $tag = $tagLink->firstChild->textContent;
                if (str_contains(mb_strtolower($tag), $tagContains)) {
                    return $this->makeArticle($snippet);
                }
            }
        }
        return null;
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
