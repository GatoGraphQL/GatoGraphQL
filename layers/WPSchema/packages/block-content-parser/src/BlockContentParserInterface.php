<?php

declare(strict_types=1);

namespace PoPWPSchema\BlockContentParser;

use WP_Error;

interface BlockContentParserInterface
{
    /**
	 * @param string $customPostContent HTML content of a post.
	 * @param int|null $customPostID ID of the post being parsed. Required for blocks containing meta-sourced attributes and some block filters.
	 * @param array<string,mixed> $filterOptions An associative array of options for filtering blocks. Can contain keys:
	 *              'exclude': An array of block names to block from the response.
	 *              'include': An array of block names that are allowed in the response.
	 *
	 * @return array<string,mixed>|WP_Error
	 */
	public function parse(
        string $customPostContent,
        ?int $customPostID = null,
        array $filterOptions = [],
    ): array|WP_Error;
}
