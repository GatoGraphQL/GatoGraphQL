<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\Services\Taxonomies;

use GatoGraphQL\GatoGraphQL\Services\Taxonomies\GraphQLEndpointCategoryTaxonomy as UpstreamGraphQLEndpointCategoryTaxonomy;

class GraphQLEndpointCategoryTaxonomy extends UpstreamGraphQLEndpointCategoryTaxonomy
{
    protected function isPublic(): bool
    {
        return false;
    }

    protected function isPubliclyQueryable(): bool
    {
        return true;
    }
}
