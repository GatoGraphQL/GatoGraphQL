<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ASTNodes;

use PoP\GraphQLParser\Spec\Parser\Location;

/**
 * For the AST nodes provided by this Factory (acting
 * as a Singleton model), provide a single instance,
 * so that comparing the object by reference when storing
 * it on SplObjectStorage objects works well
 */
class ASTNodesFactory
{
    public static ?Location $nonSpecificLocation = null;

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
        if (self::$nonSpecificLocation === null) {
            self::$nonSpecificLocation = new Location(-1, -1);
        }
        return self::$nonSpecificLocation;
    }
}
