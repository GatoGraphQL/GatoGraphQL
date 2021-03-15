<?php

declare(strict_types=1);

namespace Leoloso\ExamplesForPoP\Container\CompilerPasses;

use PoP\API\PersistedQueries\PersistedQueryManagerInterface;
use PoP\API\PersistedQueries\PersistedQueryUtils;
use PoP\Root\Container\CompilerPasses\AbstractCompilerPass;
use PoP\Root\Container\ContainerBuilderWrapperInterface;
use PoP\Translation\Facades\SystemTranslationAPIFacade;

class ConfigurePersistedQueryCompilerPass extends AbstractCompilerPass
{
    /**
     * GraphQL persisted query for Introspection query
     */
    protected function doProcess(ContainerBuilderWrapperInterface $containerBuilderWrapper): void
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
        $persistedQueryManagerDefinition = $containerBuilderWrapper->getDefinition(PersistedQueryManagerInterface::class);
        $persistedQueryManagerDefinition->addMethodCall(
            'addPersistedQuery',
            [
                'contentMesh',
                PersistedQueryUtils::removeWhitespaces($contentMeshPersistedQuery),
                $translationAPI->__('Retrieve data from the mesh services and create a \'content mesh\'', 'examples-for-pop')
            ]
        );
        $persistedQueryManagerDefinition->addMethodCall(
            'addPersistedQuery',
            [
                'userProps',
                PersistedQueryUtils::removeWhitespaces($userPropsPersistedQuery),
                $translationAPI->__('Pre-defined set of user properties', 'examples-for-pop')
            ]
        );
    }
}
