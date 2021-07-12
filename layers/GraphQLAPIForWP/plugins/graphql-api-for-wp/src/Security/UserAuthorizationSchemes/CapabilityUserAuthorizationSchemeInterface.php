<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes;

interface CapabilityUserAuthorizationSchemeInterface extends UserAuthorizationSchemeInterface
{
    public function getSchemaEditorAccessCapability(): string;
}
