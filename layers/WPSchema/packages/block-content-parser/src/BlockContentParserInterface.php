<?php

declare(strict_types=1);

namespace PoPWPSchema\BlockContentParser;

use PoPWPSchema\BlockContentParser\Exception\BlockContentParserException;
use stdClass;

interface BlockContentParserInterface
{
    /**
	 * @param int $customPostID ID of the post being parsed. Required for blocks containing meta-sourced attributes and some block filters.
	 * @param array<string,mixed> $filterOptions An associative array of options for filtering blocks. Can contain keys:
	 *              'exclude': An array of block names to block from the response.
	 *              'include': An array of block names that are allowed in the response.
	 *
	 * @return array<stdClass>|null `null` if the custom post does not exist
     * @throws BlockContentParserException If there is any error processing the content
	 */
	public function parseCustomPostIntoBlockDataItems(
        int $customPostID,
        array $filterOptions = [],
    ): ?array;

    /**
	 * @param string $customPostContent HTML content of a post.
	 * @param array<string,mixed> $filterOptions An associative array of options for filtering blocks. Can contain keys:
	 *              'exclude': An array of block names to block from the response.
	 *              'include': An array of block names that are allowed in the response.
	 *
	 * @return array<stdClass>
     * @throws BlockContentParserException If there is any error processing the content
	 */
	public function parseCustomPostContentIntoBlockDataItems(
        string $customPostContent,
        array $filterOptions = [],
    ): array;
}
