<?php

declare(strict_types=1);

namespace PoPWPSchema\BlockContentParser;

use PoP\DOMCrawler\CrawlerFactoryInterface;
use PoP\Root\Services\BasicServiceTrait;

class BlockContentParser implements BlockContentParserInterface
{
    use BasicServiceTrait;
    
    private ?CrawlerFactoryInterface $crawlerFactory = null;
    
    final public function setCrawlerFactory(CrawlerFactoryInterface $crawlerFactory): void
    {
        $this->crawlerFactory = $crawlerFactory;
    }
    final protected function getCrawlerFactory(): CrawlerFactoryInterface
    {
        /** @var CrawlerFactoryInterface */
        return $this->crawlerFactory ??= $this->instanceManager->getInstance(CrawlerFactoryInterface::class);
    }
}
