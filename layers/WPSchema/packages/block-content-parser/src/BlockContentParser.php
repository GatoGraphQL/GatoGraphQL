<?php

declare(strict_types=1);

namespace PoPWPSchema\BlockContentParser;

use PoP\DOMCrawler\CrawlerFactoryInterface;
use PoP\Root\Services\BasicServiceTrait;
use WP_Error;

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

    /**
	 * @param string $post_content HTML content of a post.
	 * @param int|null $post_id ID of the post being parsed. Required for blocks containing meta-sourced attributes and some block filters.
	 * @param array<string,mixed> $filter_options An associative array of options for filtering blocks. Can contain keys:
	 *              'exclude': An array of block names to block from the response.
	 *              'include': An array of block names that are allowed in the response.
	 *
	 * @return array<string,mixed>|WP_Error
	 */
	public function parse(
        string $post_content,
        ?int $post_id = null,
        array $filter_options = [],
    ): array|WP_Error {
        return [];
    }
}
