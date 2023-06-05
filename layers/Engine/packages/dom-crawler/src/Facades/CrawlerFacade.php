<?php

declare(strict_types=1);

namespace PoP\DOMCrawler\Facades;

use PoP\Root\App;
use PoP\DOMCrawler\CrawlerInterface;

class CrawlerFacade
{
    public static function getInstance(): CrawlerInterface
    {
        /**
         * @var CrawlerInterface
         */
        $service = App::getContainer()->get(CrawlerInterface::class);
        return $service;
    }
}
