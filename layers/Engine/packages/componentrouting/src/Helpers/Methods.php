<?php

declare(strict_types=1);

namespace PoP\ComponentRouting\Helpers;

use PoP\Root\Helpers\Methods as RootMethods;

class Methods
{
    /**
     * @param mixed[] $maybeSubset
     * @param mixed[] $set
     */
    public static function arrayIsSubset(array $maybeSubset, array $set): bool
    {
        return $maybeSubset == RootMethods::arrayIntersectAssocRecursive($maybeSubset, $set);
    }
}
