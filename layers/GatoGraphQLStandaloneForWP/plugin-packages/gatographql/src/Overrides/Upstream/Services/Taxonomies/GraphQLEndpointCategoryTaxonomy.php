<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\Services\Taxonomies;

use GatoGraphQL\GatoGraphQL\Services\Taxonomies\GraphQLEndpointCategoryTaxonomy as UpstreamGraphQLEndpointCategoryTaxonomy;

class GraphQLEndpointCategoryTaxonomy extends UpstreamGraphQLEndpointCategoryTaxonomy
{
    public function showInMenu(): ?string
    {
        if (!$this->isServiceEnabled()) {
            return null;
        }
        // Show in menu only if any of the attached CPTs is shown in menu
        return parent::showInMenu();
    }

    protected function isPublic(): bool
    {
        return false;
    }

    protected function isPubliclyQueryable(): bool
    {
        return true;
    }
}
