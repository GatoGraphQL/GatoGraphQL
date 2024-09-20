<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationSchemes\DownToAuthorRoleUserAuthorizationScheme;
use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationSchemes\UserAuthorizationSchemeInterface;

trait DownToAuthorSchemaEditingAccessModifyPluginSettingsFixtureEndpointWebserverRequestTestTrait
{
    protected function getUserAuthorizationScheme(): UserAuthorizationSchemeInterface
    {
        return new DownToAuthorRoleUserAuthorizationScheme();
    }
}
