<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationSchemes\DownToEditorRoleUserAuthorizationScheme;
use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationSchemes\UserAuthorizationSchemeInterface;

trait DownToEditorSchemaEditingAccessModifyPluginSettingsFixtureEndpointWebserverRequestTestTrait
{
    protected function getUserAuthorizationScheme(): UserAuthorizationSchemeInterface
    {
        return new DownToEditorRoleUserAuthorizationScheme();
    }
}
