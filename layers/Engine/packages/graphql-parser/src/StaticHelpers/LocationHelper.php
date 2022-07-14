<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\StaticHelpers;

use PoP\GraphQLParser\Spec\Parser\Location;

class LocationHelper
{
    /**
     * Use a non-existing location to indicate that the
     * AST node was created on runtime, and is not to be
     * found on the GraphQL query.
     *
     * Eg of runtime AST nodes:
     *
     * - @resolveValueAndMerge
     * - @serializeLeafOutputTypeValues
     * - Directives added via `getMandatoryDirectivesForFields`
     *
     * This Location will not be printed on the GraphQL response
     */
    public static function getNonSpecificLocation(): Location
    {
        return new Location(-1, -1);
    }
}
