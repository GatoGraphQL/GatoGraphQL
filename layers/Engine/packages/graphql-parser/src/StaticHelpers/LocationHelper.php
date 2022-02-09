<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\StaticHelpers;

use PoP\GraphQLParser\Spec\Parser\Location;

/**
 * Temporary class until fully migrating the Engine to GraphQL
 */
class LocationHelper
{
    public static function getNonSpecificLocation(): Location
    {
        return new Location(1, 1);
    }
}
