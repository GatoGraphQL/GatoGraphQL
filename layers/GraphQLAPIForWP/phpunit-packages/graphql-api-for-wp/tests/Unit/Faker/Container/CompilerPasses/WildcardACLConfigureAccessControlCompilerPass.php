<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Unit\Faker\Container\CompilerPasses;

use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InterfaceType\IsCustomPostInterfaceTypeResolver;
use PoPCMSSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPCMSSchema\UserStateAccessControl\Services\AccessControlGroups as UserStateAccessControlGroups;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\MandatoryDirectivesByConfiguration\Constants\WildcardConfigurationValues;
use PoP\Root\Container\CompilerPasses\AbstractCompilerPass;
use PoP\Root\Container\ContainerBuilderWrapperInterface;

class WildcardACLConfigureAccessControlCompilerPass extends AbstractCompilerPass
{
    protected function doProcess(ContainerBuilderWrapperInterface $containerBuilderWrapper): void
    {
        $accessControlManagerDefinition = $containerBuilderWrapper->getDefinition(AccessControlManagerInterface::class);
        $accessControlManagerDefinition->addMethodCall(
            'addEntriesForFields',
            [
                UserStateAccessControlGroups::STATE,
                [
                    // Wildcard type or interface
                    [
                        WildcardConfigurationValues::MATCH_ANY,
                        'users',
                        UserStates::IN,
                    ],
                    // Wildcard field on type
                    [
                        CommentObjectTypeResolver::class,
                        WildcardConfigurationValues::MATCH_ANY,
                        UserStates::IN,
                    ],
                    // Wildcard field on interface
                    [
                        IsCustomPostInterfaceTypeResolver::class,
                        WildcardConfigurationValues::MATCH_ANY,
                        UserStates::IN,
                    ],
                ]
            ]
        );
    }
}
