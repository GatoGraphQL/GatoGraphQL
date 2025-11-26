<?php

declare(strict_types=1);

namespace PoPWPSchema\BlockContentParser;

use PoPWPSchema\BlockContentParser\Exception\BlockContentParserException;
use PoPWPSchema\BlockContentParser\ObjectModels\BlockContentParserPayload;
use WP_Post;

interface BlockContentParserInterface
{
    /**
     * @param array<string,mixed> $options An associative array of options. Can contain keys:
     *              'filter': An associative array of options for filtering blocks. Can contain keys:
     *                      'exclude': An array of block names to block from the response.
     *                      'include': An array of block names that are allowed in the response.
     *              'include-inner-content': Indicate if to include the "innerContent" property
     *              'use-html5-parser': Indicate if to use the HTML5 parser.
     *                  Default: true.
     *                  When parsing HTML5, the parser will convert non-breaking space "U+00A0" to "&nbsp;" entities,
     *                  and then the search/replace in "rawContent" will fail.
     *                  Then allow to disable the HTML5 parser.
     *
     * @return BlockContentParserPayload|null `null` if the custom post does not exist
     * @throws BlockContentParserException If there is any error processing the content
     */
    public function parseCustomPostIntoBlockDataItems(
        WP_Post|int $customPostObjectOrID,
        array $options = [],
    ): ?BlockContentParserPayload;

    /**
     * @param string $customPostContent HTML content of a post.
     * @param array<string,mixed> $filterOptions An associative array of options for filtering blocks. Can contain keys:
     *              'exclude': An array of block names to block from the response.
     *              'include': An array of block names that are allowed in the response.
     *
     * @throws BlockContentParserException If there is any error processing the content
     */
    public function parseCustomPostContentIntoBlockDataItems(
        string $customPostContent,
        array $filterOptions = [],
    ): BlockContentParserPayload;
}
