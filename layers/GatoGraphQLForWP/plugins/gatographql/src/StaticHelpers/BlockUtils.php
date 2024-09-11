<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

class BlockUtils
{
    /**
     * @param array<array<string,mixed>> $blockDataItems
     * @return array<array<string,mixed>>
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
                ];
            },
            $blockDataItems
        );
    }
}
