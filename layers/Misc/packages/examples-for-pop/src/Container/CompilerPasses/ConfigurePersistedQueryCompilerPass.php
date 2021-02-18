<?php

declare(strict_types=1);

namespace Leoloso\ExamplesForPoP\Container\CompilerPasses;

use PoP\API\PersistedQueries\PersistedQueryManagerInterface;
use PoP\API\PersistedQueries\PersistedQueryUtils;
use PoP\Translation\Facades\SystemTranslationAPIFacade;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ConfigurePersistedQueryCompilerPass implements CompilerPassInterface
{
    /**
     * GraphQL persisted query for Introspection query
     */
    public function process(ContainerBuilder $containerBuilder): void
    {
        // Persisted queries
        $contentMeshPersistedQuery = <<<EOT
        --contentMesh
EOT;
        $userPropsPersistedQuery = <<<EOT
        users.
            id|
            name|
            url|
            posts.
                id|
                title|
                url
EOT;
        // Inject the values into the service
        $translationAPI = SystemTranslationAPIFacade::getInstance();
        $persistedQueryManagerDefinition = $containerBuilder->getDefinition(PersistedQueryManagerInterface::class);
        $persistedQueryManagerDefinition->addMethodCall(
            'contentMesh',
            [
                PersistedQueryUtils::removeWhitespaces($contentMeshPersistedQuery),
                $translationAPI->__('Retrieve data from the mesh services and create a \'content mesh\'', 'examples-for-pop')
            ]
        );
        $persistedQueryManagerDefinition->addMethodCall(
            'userProps',
            [
                PersistedQueryUtils::removeWhitespaces($userPropsPersistedQuery),
                $translationAPI->__('Pre-defined set of user properties', 'examples-for-pop')
            ]
        );
    }
}
