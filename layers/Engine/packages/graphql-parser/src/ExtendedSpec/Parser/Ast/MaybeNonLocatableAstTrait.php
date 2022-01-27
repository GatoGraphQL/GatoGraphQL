<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Location;

trait MaybeNonLocatableAstTrait
{
    /**
     * For the SiteBuilder, the generated GraphQL query will have no Location.
     * Because the parent class does need a Location, create a "dummy" one,
     * with coordinates [0, 0]
     */
    public function getLocationOrPseudoLocation(?Location $location = null): Location
    {
        return $location ?? new Location(0, 0);
    }
}
