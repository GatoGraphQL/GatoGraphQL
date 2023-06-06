<?php

declare(strict_types=1);

namespace PoP\DOMCrawler\Facades;

use PoP\Root\App;
use PoP\DOMCrawler\CrawlerFactoryInterface;

class CrawlerFactoryFacade
{
    public static function getInstance(): CrawlerFactoryInterface
    {
        /**
         * @var CrawlerFactoryInterface
         */
        $service = App::getContainer()->get(CrawlerFactoryInterface::class);
        return $service;
    }
}
