<?php

declare(strict_types=1);

namespace PoP\DOMCrawler;

use DOMNodeList;
use DOMNode;
use Symfony\Component\DomCrawler\Crawler;

interface CrawlerInterface
{
    /**
     * @param DOMNodeList|DOMNode|DOMNode[]|string|null $node A Node to use as the base for the crawling
     */
    public function createCrawler(
        DOMNodeList|DOMNode|array|string $node = null,
        string $uri = null,
        string $baseHref = null,
        bool $useHtml5Parser = true,
    ): Crawler;
}
