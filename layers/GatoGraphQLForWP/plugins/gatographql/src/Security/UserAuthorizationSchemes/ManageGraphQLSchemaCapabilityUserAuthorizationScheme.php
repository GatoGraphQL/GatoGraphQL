<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Security\UserAuthorizationSchemes;

use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationSchemes\AbstractByCapabilityUserAuthorizationScheme;

class ManageGraphQLSchemaCapabilityUserAuthorizationScheme extends AbstractByCapabilityUserAuthorizationScheme
{
    public function getSchemaEditorAccessCapability(): string
    {
        return constant('GATOGRAPHQL_CAPABILITY_MANAGE_GRAPHQL_SCHEMA');
    }

    public function getPriority(): int
    {
        return 2;
    }
}
