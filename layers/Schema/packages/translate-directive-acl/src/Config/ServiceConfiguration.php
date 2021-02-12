<?php

declare(strict_types=1);

namespace PoPSchema\TranslateDirectiveACL\Config;

use PoPSchema\TranslateDirectiveACL\Environment;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\Root\Container\ContainerBuilderUtils;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoPSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPSchema\TranslateDirective\DirectiveResolvers\AbstractTranslateDirectiveResolver;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups as UserRolesAccessControlGroups;
use PoPSchema\UserStateAccessControl\Services\AccessControlGroups as UserStateAccessControlGroups;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure(): void
    {
        if (Environment::userMustBeLoggedInToAccessTranslateDirective()) {
            ContainerBuilderUtils::injectValuesIntoService(
                AccessControlManagerInterface::class,
                'addEntriesForDirectives',
                UserStateAccessControlGroups::STATE,
                [
                    [AbstractTranslateDirectiveResolver::class, UserStates::IN],
                ]
            );
        }
        if ($roles = Environment::anyRoleLoggedInUserMustHaveToAccessTranslateDirective()) {
            ContainerBuilderUtils::injectValuesIntoService(
                AccessControlManagerInterface::class,
                'addEntriesForDirectives',
                UserRolesAccessControlGroups::ROLES,
                [
                    [AbstractTranslateDirectiveResolver::class, $roles],
                ]
            );
        }
        if ($capabilities = Environment::anyCapabilityLoggedInUserMustHaveToAccessTranslateDirective()) {
            ContainerBuilderUtils::injectValuesIntoService(
                AccessControlManagerInterface::class,
                'addEntriesForDirectives',
                UserRolesAccessControlGroups::CAPABILITIES,
                [
                    [AbstractTranslateDirectiveResolver::class, $capabilities],
                ]
            );
        }
    }
}
