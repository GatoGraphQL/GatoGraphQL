<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Unit\Faker\Container\CompilerPasses;

use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InterfaceType\IsCustomPostInterfaceTypeResolver;
use PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPCMSSchema\UserStateAccessControl\ConfigurationEntries\UserStates;
use PoPCMSSchema\UserStateAccessControl\Services\AccessControlGroups as UserStateAccessControlGroups;
use PoP\AccessControl\Schema\SchemaModes;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Root\Container\CompilerPasses\AbstractCompilerPass;
use PoP\Root\Container\ContainerBuilderWrapperInterface;

class PrivateACLConfigureAccessControlCompilerPass extends AbstractCompilerPass
{
    protected function doProcess(ContainerBuilderWrapperInterface $containerBuilderWrapper): void
    {
        $accessControlManagerDefinition = $containerBuilderWrapper->getDefinition(AccessControlManagerInterface::class);
        $accessControlManagerDefinition->addMethodCall(
            'addEntriesForFields',
            [
                UserStateAccessControlGroups::STATE,
                [
                    // Type or interface (test on QueryRoot)
                    [
                        RootObjectTypeResolver::class,
                        'users',
                        UserStates::IN,
                        SchemaModes::PRIVATE_SCHEMA_MODE,
                    ],
                    // Type or interface (test on PostCategory)
                    [
                        PostCategoryObjectTypeResolver::class,
                        'count',
                        UserStates::IN,
                        SchemaModes::PRIVATE_SCHEMA_MODE,
                    ],
                    // Field on type
                    [
                        CommentObjectTypeResolver::class,
                        'content',
                        UserStates::IN,
                        SchemaModes::PRIVATE_SCHEMA_MODE,
                    ],
                    // Field on interface
                    [
                        IsCustomPostInterfaceTypeResolver::class,
                        'title',
                        UserStates::IN,
                        SchemaModes::PRIVATE_SCHEMA_MODE,
                    ],
                ]
            ]
        );
    }
}
