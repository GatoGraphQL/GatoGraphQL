<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes;

class ManageOptionsCapabilityUserAuthorizationScheme extends AbstractCapabilityUserAuthorizationScheme
{
    /**
     * Only the admin has capability "manage_options"
     */
    public function getSchemaEditorAccessCapability(): string
    {
        return 'manage_options';
    }
}
