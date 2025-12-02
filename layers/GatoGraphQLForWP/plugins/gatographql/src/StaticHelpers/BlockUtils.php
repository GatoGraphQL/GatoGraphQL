<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use function get_bloginfo;
use function serialize_blocks;
use function version_compare;

class BlockUtils
{
    /**
     * @param array<array<string,mixed>> $blockDataItems
     * @return mixed[] {
     *     Array of block structures.
     *
     *     @type array ...$0 {
     *         A representative array of a single parsed block object. See WP_Block_Parser_Block.
     *
     *         @type string   $blockName    Name of block.
     *         @type array    $attrs        Attributes from block comment delimiters.
     *         @type array[]  $innerBlocks  List of inner blocks. An array of arrays that
     *                                      have the same structure as this one.
     *         @type string   $innerHTML    HTML from inside block comment delimiters.
     *         @type array    $innerContent List of string fragments and null markers where
     *                                      inner blocks were found.
     *     }
     * }
     */
    public static function addInnerContentToBlockAttrs(array $blockDataItems): array
    {
        return array_map(
            function (array $blockDataItem): array {
                // Must add an empty array for each of the innerBlocks
                $innerContentItems = array_pad([], count($blockDataItem['innerBlocks'] ?? []), []);
                return [
                    ...$blockDataItem,
                    'innerContent' => $innerContentItems,
                    'innerHTML' => '', // Required for serialize_blocks()
                ];
            },
            $blockDataItems
        );
    }

    /**
     * Serialize blocks and fix issues:
     *
     * - WordPress 6.9 encodes "\\" as "\u005c"
     *
     * @param array<array<string,mixed>> $blockDataItems
     */
    public static function serializeBlocksContent(array $blockDataItems): string
    {
        $serializedContent = serialize_blocks(self::addInnerContentToBlockAttrs($blockDataItems));
        
        /**
         * Bug in WordPress 6.9: All "\\" characters are encoded as "\u005c" during JSON encoding
         * in serialize_blocks. Fix by converting them back to "\\".
         */
        if (version_compare(get_bloginfo('version'), '6.9', '>=')) {
            $serializedContent = str_replace('\\u005c', '\\\\', $serializedContent);
        }
        
        return $serializedContent;
    }
}
