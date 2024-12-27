<?php

declare(strict_types=1);

namespace PoPWPSchema\BlockContentParser;

use DOMNode;
use PoPWPSchema\BlockContentParser\Exception\BlockContentParserException;
use PoPWPSchema\BlockContentParser\ObjectModels\BlockContentParserPayload;
use PoP\ComponentModel\StaticHelpers\MethodHelpers;
use PoP\DOMCrawler\Crawler;
use PoP\Root\Services\AbstractBasicService;
use stdClass;
use Throwable;
use WP_Block_Type;
use WP_Block_Type_Registry;
use WP_Error;
use WP_Post;

use function get_post;
use function has_blocks;
use function parse_blocks;

/**
 * This class is based on class `ContentParser`
 * from project `Automattic/vip-block-data-api`,
 * released under `v1.0.0`.
 *
 * @see https://github.com/Automattic/vip-block-data-api/blob/585e000e9fa2388e2c4039bde6dd324620ab0ff9/src/parser/content-parser.php
 * @see https://github.com/Automattic/vip-block-data-api/tree/1.0.0
 */
class BlockContentParser extends AbstractBasicService implements BlockContentParserInterface
{
    private bool $includeInnerContent = false;

    /**
     * @param array<string,mixed> $options An associative array of options. Can contain keys:
     *              'filter': An associative array of options for filtering blocks. Can contain keys:
     *                      'exclude': An array of block names to block from the response.
     *                      'include': An array of block names that are allowed in the response.
     *              'include-inner-content': Indicate if to include the "innerContent" property
     *
     * @return BlockContentParserPayload|null `null` if the custom post does not exist
     * @throws BlockContentParserException If there is any error processing the content
     */
    public function parseCustomPostIntoBlockDataItems(
        WP_Post|int $customPostObjectOrID,
        array $options = [],
    ): ?BlockContentParserPayload {
        if ($customPostObjectOrID instanceof WP_Post) {
            $customPost = $customPostObjectOrID;
        } else {
            /** @var int */
            $customPostID = $customPostObjectOrID;
            /** @var WP_Post|null */
            $customPost = get_post($customPostID);
            if ($customPost === null) {
                return null;
            }
        }
        $customPostContent = $customPost->post_content;
        // If the post has no content, don't parse it or it'll produce an error
        if ($customPostContent === '') {
            return new BlockContentParserPayload(
                [],
                null
            );
        }
        $this->includeInnerContent = $options['include-inner-content'] ?? false;
        $parsedBlockData = $this->parse($customPostContent, $customPost->ID, $options['filter'] ?? []);
        return $this->processParsedBlockData($parsedBlockData);
    }

    /**
     * @param mixed[]|WP_Error $parsedBlockData
     * @throws BlockContentParserException
     */
    protected function processParsedBlockData(array|WP_Error $parsedBlockData): BlockContentParserPayload
    {
        if ($parsedBlockData instanceof WP_Error) {
            throw new BlockContentParserException($parsedBlockData);
        }

        /** @var array<array<string,mixed>> */
        $parsedBlockDataItems = $parsedBlockData['blocks'];
        return new BlockContentParserPayload(
            $this->castBlockDataItemsToObject($parsedBlockDataItems),
            $parsedBlockData['warnings'] ?? null
        );
    }

    /**
     * Convert each of the block data items from array to
     * object, and iteratively for its inner blocks.
     *
     * @param array<array<string,mixed>> $blockDataItems
     * @return array<stdClass>
     */
    protected function castBlockDataItemsToObject(array $blockDataItems): array
    {
        return array_map(
            /**
             * @param array<string,mixed> $item
             */
            function (array $item): stdClass {
                // Convert the block to stdClass
                $item = (object) $item;

                /**
                 * Convert the block attributes to stdClass,
                 * and all inner associative arrays too
                 */
                if (isset($item->attributes)) {
                    /** @var array<string,mixed>|stdClass */
                    $blockAttributes = $item->attributes;
                    $item->attributes = (object) MethodHelpers::recursivelyConvertAssociativeArrayToStdClass((array) $blockAttributes);
                }

                // Recursively call for the block's inner blocks'
                if (isset($item->innerBlocks)) {
                    /** @var array<array<string,mixed>> */
                    $blockInnerBlockDataItems = $item->innerBlocks;
                    $item->innerBlocks = $this->castBlockDataItemsToObject($blockInnerBlockDataItems);
                }

                return $item;
            },
            $blockDataItems
        );
    }

    /**
     * @param array<string,mixed> $options An associative array of options. Can contain keys:
     *              'filter': An associative array of options for filtering blocks. Can contain keys:
     *                      'exclude': An array of block names to block from the response.
     *                      'include': An array of block names that are allowed in the response.
     *              'include-inner-content': Indicate if to include the "innerContent" property
     *
     * @throws BlockContentParserException If there is any error processing the content
     */
    public function parseCustomPostContentIntoBlockDataItems(
        string $customPostContent,
        array $options = [],
    ): BlockContentParserPayload {
        $this->includeInnerContent = $options['include-inner-content'] ?? false;
        $parsedBlockData = $this->parse($customPostContent, null, $options['filter'] ?? []);
        return $this->processParsedBlockData($parsedBlockData);
    }

    /**
     * ----------------------------------------------------------------
     *
     * All code below has been copied from `ContentParser`
     * from project `Automattic/vip-block-data-api`.
     *
     * @see https://github.com/Automattic/vip-block-data-api/blob/585e000e9fa2388e2c4039bde6dd324620ab0ff9/src/parser/content-parser.php
     *
     * ----------------------------------------------------------------
     */

    protected WP_Block_Type_Registry $block_registry;
    protected ?int $post_id;
    /** @var string[] */
    protected array $warnings = [];

    public function __construct(WP_Block_Type_Registry|null $block_registry = null)
    {
        if (null === $block_registry) {
            $block_registry = WP_Block_Type_Registry::get_instance();
        }

        $this->block_registry = $block_registry;
    }

    /**
     * @param array<string,mixed> $block
     * @param array<string,mixed> $filter_options
     *
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    public function should_block_be_included(array $block, string $block_name, array $filter_options): bool
    {
        $is_block_included = true;

        if (! empty($filter_options['include'])) {
            $is_block_included = in_array($block_name, $filter_options['include']);
        } elseif (! empty($filter_options['exclude'])) {
            $is_block_included = ! in_array($block_name, $filter_options['exclude']);
        }

        /**
         * Filter out blocks from the blocks output
         *
         * @param bool   $is_block_included True if the block should be included, or false to filter it out.
         * @param string $block_name    The name of the parsed block, e.g. 'core/paragraph'.
         * @param string $block         The result of parse_blocks() for this block.
         *                              Contains 'blockName', 'attrs', 'innerHTML', and 'innerBlocks' keys.
         */
        // return apply_filters( 'vip_block_data_api__allow_block', $is_block_included, $block_name, $block );
        return $is_block_included;
    }

    /**
     * @param string $post_content HTML content of a post.
     * @param int|null $post_id ID of the post being parsed. Required for blocks containing meta-sourced attributes and some block filters.
     * @param array<string,mixed> $filter_options An associative array of options for filtering blocks. Can contain keys:
     *              'exclude': An array of block names to block from the response.
     *              'include': An array of block names that are allowed in the response.
     *
     * @return mixed[]|WP_Error
     */
    protected function parse(string $post_content, ?int $post_id = null, array $filter_options = []): array|WP_Error
    {
        if (isset($filter_options['exclude']) && isset($filter_options['include'])) {
            // return new WP_Error('vip-block-data-api-invalid-params', 'Cannot provide blocks to exclude and include at the same time', [ 'status' => 400 ]);
            return new WP_Error(
                'vip-block-data-api-invalid-params',
                \__('Cannot provide blocks to exclude and include at the same time', 'gatographql'),
                [ 'status' => 400 ]
            );
        }

        $this->post_id  = $post_id;
        $this->warnings = [];

        $has_blocks = has_blocks($post_content);

        if (! $has_blocks) {
            // $error_message = join(' ', [
            //     sprintf('Error parsing post ID %d: This post does not appear to contain block content.', $post_id),
            //     'The VIP Block Data API is designed to parse Gutenberg blocks and can not read classic editor content.',
            // ]);
            $error_message = join(' ', [
                sprintf(
                    \__('Error parsing post ID %d: This post does not appear to contain block content.', 'gatographql'),
                    $post_id
                ),
                \__('This fields is designed to parse Gutenberg blocks and can not read classic editor content.', 'gatographql'),
            ]);

            return new WP_Error('vip-block-data-api-no-blocks', $error_message, [ 'status' => 400 ]);
        }

        $parsing_error = false;

        try {
            $blocks = parse_blocks($post_content);
            $blocks = array_values(array_filter($blocks, function ($block) {
                $is_whitespace_block = ( null === $block['blockName'] && empty(trim($block['innerHTML'])) );
                return ! $is_whitespace_block;
            }));

            $registered_blocks = $this->block_registry->get_all_registered();

            $sourced_blocks = array_map(function ($block) use ($registered_blocks, $filter_options) {
                return $this->source_block($block, $registered_blocks, $filter_options);
            }, $blocks);

            $sourced_blocks = array_values(array_filter($sourced_blocks));

            $result = [
                'blocks' => $sourced_blocks,
            ];

            if (! empty($this->warnings)) {
                $result['warnings'] = $this->warnings;
            }

            // Debug output
            if ($this->is_debug_enabled()) {
                $result['debug'] = [
                    'blocks_parsed' => $blocks,
                    'content'       => $post_content,
                ];
            }
        } catch (Throwable $error) {
            $parsing_error = $error;
        }

        if ($parsing_error) {
            $error_message = sprintf('Error parsing post ID %d: %s', $post_id, /*$parsing_error->getMessage()*/\__('This post either does not contain block content, or its block content has errors. (Edit the post within the WordPress editor, fix the errors, save, and try again.)', 'gatographql'));
            return new WP_Error('vip-block-data-api-parser-error', $error_message, [
                'status'  => 500,
                'details' => $parsing_error->__toString(),
            ]);
        } else {
            return $result;
        }
    }

    /**
     * @param array<string,mixed> $block
     * @param WP_Block_Type[] $registered_blocks
     * @param array<string,mixed> $filter_options
     *
     * @return array<string,mixed>|null
     *
     * @access private
     *
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    protected function source_block(array $block, array $registered_blocks, array $filter_options): ?array
    {
        $block_name = $block['blockName'];

        if (! $this->should_block_be_included($block, $block_name, $filter_options)) {
            return null;
        }

        if (! isset($registered_blocks[ $block_name ])) {
            $this->add_missing_block_warning($block_name);
        }

        $block_definition            = $registered_blocks[ $block_name ] ?? null;
        $block_definition_attributes = $block_definition->attributes ?? [];

        $block_attributes = $block['attrs'];

        foreach ($block_definition_attributes as $block_attribute_name => $block_attribute_definition) {
            $attribute_source        = $block_attribute_definition['source'] ?? null;
            $attribute_default_value = $block_attribute_definition['default'] ?? null;

            if (null === $attribute_source) {
                // Unsourced attributes are stored in the block's delimiter attributes, skip DOM parser

                if (isset($block_attributes[ $block_attribute_name ])) {
                    // Attribute is already set in the block's delimiter attributes, skip
                    continue;
                } elseif (null !== $attribute_default_value) {
                    // Attribute is unset and has a default value, use default value
                    $block_attributes[ $block_attribute_name ] = $attribute_default_value;
                    continue;
                } else {
                    // Attribute is unset and has no default value, skip
                    continue;
                }
            }

            // Specify a manual doctype so that the parser will use the HTML5 parser
            $crawler = new Crawler(sprintf('<!doctype html><html><body>%s</body></html>', $block['innerHTML']));

            // Enter the <body> tag for block parsing
            $crawler = $crawler->filter('body');

            $attribute_value = $this->source_attribute($crawler, $block_attribute_definition);

            if (null !== $attribute_value) {
                $block_attributes[ $block_attribute_name ] = $attribute_value;
            }
        }

        $sourced_block = [
            'name'          => $block_name,
            'attributes'    => $block_attributes,
        ];

        /**
         * Gato GraphQL addition
         */
        if ($this->includeInnerContent) {
            $sourced_block['innerContent'] = $block['innerContent'];
        }

        if (isset($block['innerBlocks'])) {
            $inner_blocks = array_map(function ($block) use ($registered_blocks, $filter_options) {
                return $this->source_block($block, $registered_blocks, $filter_options);
            }, $block['innerBlocks']);

            $inner_blocks = array_values(array_filter($inner_blocks));

            if (! empty($inner_blocks)) {
                $sourced_block['innerBlocks'] = $inner_blocks;
            }
        }

        if ($this->is_debug_enabled()) {
            $sourced_block['debug'] = [
                'block_definition_attributes' => $block_definition->attributes,
            ];
        }

        /**
         * Filters a block when parsing is complete.
         *
         * @param array<string,mixed> $sourced_block An associative array of parsed block data with keys 'name' and 'attribute'.
         * @param string $block_name The name of the parsed block, e.g. 'core/paragraph'.
         * @param string $post_id The post ID associated with the parsed block.
         * @param string $block The result of parse_blocks() for this block. Contains 'blockName', 'attrs', 'innerHTML', and 'innerBlocks' keys.
         */
        // $sourced_block = apply_filters( 'vip_block_data_api__sourced_block_result', $sourced_block, $block_name, $this->post_id, $block );

        // If attributes are empty, explicitly use an object to avoid encoding an empty array in JSON
        if (empty($sourced_block['attributes'])) {
            $sourced_block['attributes'] = (object) [];
        }

        return $sourced_block;
    }

    /**
     * @param array<string,mixed> $block_attribute_definition
     *
     * @return mixed[]|string|null
     *
     * @access private
     *
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    protected function source_attribute(Crawler $crawler, array $block_attribute_definition): array|string|null
    {
        $attribute_value         = null;
        $attribute_default_value = $block_attribute_definition['default'] ?? null;
        $attribute_source        = $block_attribute_definition['source'];

        // See block attribute sources:
        // https://developer.wordpress.org/block-editor/reference-guides/block-api/block-attributes/#value-source
        if ('attribute' === $attribute_source || 'property' === $attribute_source) {
            // 'property' sources were removed in 2018. Default to attribute value.
            // https://github.com/WordPress/gutenberg/pull/8276

            $attribute_value = $this->source_block_attribute($crawler, $block_attribute_definition);
        } elseif ('rich-text' === $attribute_source) {
            // Most 'html' sources were converted to 'rich-text' in WordPress 6.5.
            // https://github.com/WordPress/gutenberg/pull/43204

            $attribute_value = $this->source_block_rich_text($crawler, $block_attribute_definition);
        } elseif ('html' === $attribute_source) {
            $attribute_value = $this->source_block_html($crawler, $block_attribute_definition);
        } elseif ('text' === $attribute_source) {
            $attribute_value = $this->source_block_text($crawler, $block_attribute_definition);
        } elseif ('tag' === $attribute_source) {
            $attribute_value = $this->source_block_tag($crawler, $block_attribute_definition);
        } elseif ('raw' === $attribute_source) {
            $attribute_value = $this->source_block_raw($crawler, $block_attribute_definition);
        } elseif ('query' === $attribute_source) {
            $attribute_value = $this->source_block_query($crawler, $block_attribute_definition);
        } elseif ('meta' === $attribute_source) {
            $attribute_value = $this->source_block_meta($block_attribute_definition);
        } elseif ('node' === $attribute_source) {
            $attribute_value = $this->source_block_node($crawler, $block_attribute_definition);
        } elseif ('children' === $attribute_source) {
            $attribute_value = $this->source_block_children($crawler, $block_attribute_definition);
        }

        if (null === $attribute_value) {
            $attribute_value = $attribute_default_value;
        }

        return $attribute_value;
    }

    /**
     * @param array<string,mixed> $block_attribute_definition
     *
     * @return string|null
     *
     * @access private
     *
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    protected function source_block_attribute(Crawler $crawler, array $block_attribute_definition): ?string
    {
        // 'attribute' sources:
        // https://developer.wordpress.org/block-editor/reference-guides/block-api/block-attributes/#attribute-source

        $attribute_value = null;
        $attribute       = $block_attribute_definition['attribute'];
        $selector        = $block_attribute_definition['selector'] ?? null;

        if (null !== $selector) {
            $crawler = $crawler->filter($selector);
        }

        if ($crawler->count() > 0) {
            $attribute_value = $crawler->attr($attribute);
        }

        return $attribute_value;
    }

    /**
     * @param array<string,mixed> $block_attribute_definition
     *
     * @return string|null
     *
     * @access private
     *
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    protected function source_block_html(Crawler $crawler, array $block_attribute_definition): ?string
    {
        // 'html' sources:
        // https://developer.wordpress.org/block-editor/reference-guides/block-api/block-attributes/#html-source

        $attribute_value = null;
        $selector        = $block_attribute_definition['selector'] ?? null;

        if (null !== $selector) {
            $crawler = $crawler->filter($selector);
        }

        if ($crawler->count() > 0) {
            $multiline_selector = $block_attribute_definition['multiline'] ?? null;

            if (null === $multiline_selector) {
                $attribute_value = $crawler->html();
            } else {
                $multiline_parts = $crawler->filter($multiline_selector)->each(function ($node) {
                    return $node->outerHtml();
                });

                $attribute_value = join('', $multiline_parts);
            }
        }

        return $attribute_value;
    }

    /**
     * Helper function to process the `rich-text` source attribute.
     * At present, the main difference from `html` is that `rich-text` does not support multiline selectors.
     *
     * @param Crawler $crawler Crawler instance.
     * @param array<string,mixed> $block_attribute_definition Definition of the block attribute.
     *
     * @return string|null
     *
     * @access private
     *
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    protected function source_block_rich_text(Crawler $crawler, array $block_attribute_definition): ?string
    {
        // 'rich-text' sources:
        // https://github.com/WordPress/gutenberg/blob/6a42225124e69276a2deec4597a855bb504b37cc/packages/blocks/src/api/parser/get-block-attributes.js#L228-L232

        $attribute_value = null;
        $selector        = $block_attribute_definition['selector'] ?? null;

        if (null !== $selector) {
            $crawler = $crawler->filter($selector);
        }

        if ($crawler->count() > 0) {
            $attribute_value = $crawler->html();
        }

        return $attribute_value;
    }

    /**
     * @param array<string,mixed> $block_attribute_definition
     *
     * @return string|null
     *
     * @access private
     *
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    protected function source_block_text(Crawler $crawler, array $block_attribute_definition): ?string
    {
        // 'text' sources:
        // https://developer.wordpress.org/block-editor/reference-guides/block-api/block-attributes/#text-source

        $attribute_value = null;
        $selector        = $block_attribute_definition['selector'] ?? null;

        if (null !== $selector) {
            $crawler = $crawler->filter($selector);
        }

        if ($crawler->count() > 0) {
            $attribute_value = $crawler->text();
        }

        return $attribute_value;
    }

    /**
     * @param array<string,mixed> $block_attribute_definition
     *
     * @return mixed[]|null
     *
     * @access private
     *
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    protected function source_block_query(Crawler $crawler, array $block_attribute_definition): ?array
    {
        // 'query' sources:
        // https://developer.wordpress.org/block-editor/reference-guides/block-api/block-attributes/#query-source

        $query_items = $block_attribute_definition['query'];
        $selector    = $block_attribute_definition['selector'] ?? null;

        if (null !== $selector) {
            $crawler = $crawler->filter($selector);
        }

        $attribute_values = $crawler->each(function ($node) use ($query_items) {
            $attribute_value = array_map(function ($query_item) use ($node) {
                return $this->source_attribute($node, $query_item);
            }, $query_items);

            // Remove unsourced query values
            $attribute_value = array_filter($attribute_value, function ($value) {
                return null !== $value;
            });

            return $attribute_value;
        });


        return $attribute_values;
    }

    /**
     * @param array<string,mixed> $block_attribute_definition
     *
     * @return string|null
     *
     * @access private
     *
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    protected function source_block_tag(Crawler $crawler, array $block_attribute_definition): ?string
    {
        // The only current usage of the 'tag' attribute is Gutenberg core is the 'core/table' block:
        // https://github.com/WordPress/gutenberg/blob/796b800/packages/block-library/src/table/block.json#L39
        // Also see tag attribute parsing in Gutenberg:
        // https://github.com/WordPress/gutenberg/blob/6517008/packages/blocks/src/api/parser/get-block-attributes.js#L225

        $attribute_value = null;
        $selector        = $block_attribute_definition['selector'] ?? null;

        if (null !== $selector) {
            $crawler = $crawler->filter($selector);
        }

        if ($crawler->count() > 0) {
            $attribute_value = strtolower($crawler->nodeName());
        }

        return $attribute_value;
    }

    /**
     * @param array<string,mixed> $block_attribute_definition
     *
     * @return string|null
     *
     * @access private
     *
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    protected function source_block_raw(Crawler $crawler, array $block_attribute_definition): ?string
    {
        // The only current usage of the 'raw' attribute in Gutenberg core is the 'core/html' block:
        // https://github.com/WordPress/gutenberg/blob/6517008/packages/block-library/src/html/block.json#L13
        // Also see tag attribute parsing in Gutenberg:
        // https://github.com/WordPress/gutenberg/blob/6517008/packages/blocks/src/api/parser/get-block-attributes.js#L131

        $attribute_value = null;

        if ($crawler->count() > 0) {
            $attribute_value = trim($crawler->outerHtml());
            // $crawler's outerHtml() will only return the HTML of the first node in this raw HTML.
            // If the raw HTML contains multiple top-level nodes, we need to use the inner HTML of the wrapping
            // 'body' tag. This will also preserve internal whitespace in the HTML.
            $body_node = $crawler->closest('body');

            if ($body_node && $body_node->count() > 0) {
                $attribute_value = trim($body_node->html());
            }
        }

        return $attribute_value;
    }

    /**
     * @param array<string,mixed> $block_attribute_definition
     *
     * @return string|null
     *
     * @access private
     *
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    protected function source_block_meta(array $block_attribute_definition): ?string
    {
        // 'meta' sources:
        // https://developer.wordpress.org/block-editor/reference-guides/block-api/block-attributes/#meta-source

        $post = get_post($this->post_id);
        if (null === $post) {
            return null;
        }

        $meta_key            = $block_attribute_definition['meta'];
        $is_metadata_present = metadata_exists('post', $post->ID, $meta_key);

        if (! $is_metadata_present) {
            return null;
        } else {
            return get_post_meta($post->ID, $meta_key, true);
        }
    }

    /**
     * @param array<string,mixed> $block_attribute_definition
     *
     * @return mixed[]|null
     *
     * @access private
     *
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    protected function source_block_children(Crawler $crawler, array $block_attribute_definition): ?array
    {
        // 'children' attribute usage was removed from core in 2018, but not officically deprecated until WordPress 6.1:
        // https://github.com/WordPress/gutenberg/pull/44265
        // Parsing code for 'children' sources can be found here:
        // https://github.com/WordPress/gutenberg/blob/dd0504b/packages/blocks/src/api/children.js#L149

        $attribute_values = [];
        $selector         = $block_attribute_definition['selector'] ?? null;

        if (null !== $selector) {
            $crawler = $crawler->filter($selector);
        }

        if ($crawler->count() === 0) {
            // If the selector doesn't exist, return a default empty array
            return $attribute_values;
        }

        $children = $crawler->children();

        if ($children->count() === 0) {
            // 'children' attributes can be a single element. In this case, return the element value in an array.
            $attribute_values = [
                $crawler->getNode(0)->nodeValue,
            ];
        } else {
            // Use DOMDocument childNodes directly to preserve text nodes. $crawler->children() will return only
            // element nodes and omit text content.
            $children_nodes = $crawler->getNode(0)->childNodes;

            foreach ($children_nodes as $node) {
                $node_value = $this->from_dom_node($node);

                if ($node_value) {
                    $attribute_values[] = $node_value;
                }
            }
        }

        return $attribute_values;
    }

    /**
     * @param array<string,mixed> $block_attribute_definition
     *
     * @return mixed[]|string|null
     *
     * @access private
     *
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    protected function source_block_node(Crawler $crawler, array $block_attribute_definition): array|string|null
    {
        // 'node' attribute usage was removed from core in 2018, but not officically deprecated until WordPress 6.1:
        // https://github.com/WordPress/gutenberg/pull/44265
        // Parsing code for 'node' sources can be found here:
        // https://github.com/WordPress/gutenberg/blob/dd0504bd34c29b5b2824d82c8d2bb3a8d0f071ec/packages/blocks/src/api/node.js#L125

        $attribute_value = null;
        $selector        = $block_attribute_definition['selector'] ?? null;

        if (null !== $selector) {
            $crawler = $crawler->filter($selector);
        }

        $node       = $crawler->getNode(0);
        $node_value = null;

        if ($node) {
            $node_value = $this->from_dom_node($node);
        }

        if (null !== $node_value) {
            $attribute_value = $node_value;
        }

        return $attribute_value;
    }

    /**
     * Helper function to process markup used by the deprecated 'node' and 'children' sources.
     * These sources can return a representation of the DOM tree and bypass the $crawler to access DOMNodes directly.
     *
     * @return mixed[]|string|null
     *
     * @access private
     *
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    protected function from_dom_node(DOMNode $node): array|string|null
    {
        // phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- external API calls

        if (XML_TEXT_NODE === $node->nodeType) {
            // For plain text nodes, return the text directly
            $text = trim($node->nodeValue);

            // Exclude whitespace-only nodes
            if (! empty($text)) {
                return $text;
            }
        } elseif (XML_ELEMENT_NODE === $node->nodeType) {
            $children = array_map([ $this, 'from_dom_node' ], iterator_to_array($node->childNodes));

            // For element nodes, recurse and return an array of child nodes
            return [
                'type'     => $node->nodeName,
                'children' => array_filter($children),
            ];
        } else {
            return null;
        }

        return null;

        // phpcs:enable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
    }

    /**
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    protected function add_missing_block_warning(string $block_name): void
    {
        $warning_message = sprintf('Block type "%s" is not server-side registered. Sourced block attributes will not be available.', $block_name);

        if (! in_array($warning_message, $this->warnings)) {
            $this->warnings[] = $warning_message;
        }
    }

    /**
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    protected function is_debug_enabled(): bool
    {
        // return defined( 'VIP_BLOCK_DATA_API__PARSE_DEBUG' ) && constant( 'VIP_BLOCK_DATA_API__PARSE_DEBUG' ) === true;
        return false;
    }
}
