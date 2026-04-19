<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\FilterInputs;

use PoP\ComponentModel\FilterInputs\AbstractFilterInput;
use stdClass;

/**
 * Receives a `BlockTypeSupportsFilterInput` input object (a stdClass with
 * boolean/scalar fields like `autoRegister`) and merges its non-null
 * properties under the `supports` key of the dataloading query.
 */
class BlockTypeSupportsFilterInput extends AbstractFilterInput
{
    /**
     * @param array<string,mixed> $query
     */
    public function filterDataloadQueryArgs(array &$query, mixed $value): void
    {
        if (!($value instanceof stdClass)) {
            return;
        }
        $supports = [];
        foreach ((array) $value as $supportKey => $supportValue) {
            if ($supportValue === null) {
                continue;
            }
            $supports[$supportKey] = $supportValue;
        }
        if ($supports === []) {
            return;
        }
        $query['supports'] = $supports;
    }
}
