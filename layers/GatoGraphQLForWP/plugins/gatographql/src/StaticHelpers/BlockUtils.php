<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

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
}
