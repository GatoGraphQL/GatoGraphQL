<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConfigurationCache;

use GatoGraphQL\GatoGraphQL\StateManagers\AppThreadHelper;

class InternalGraphQLServerContainerCacheConfigurationManager extends ContainerCacheConfigurationManager
{
    /**
     * @param array<string,mixed> $pluginAppGraphQLServerContext
     */
    public function __construct(
        private array $pluginAppGraphQLServerContext
    ) {
    }

    /**
     * The internal server is always private, and has the
     * same configuration as the default admin endpoint.
     */
    public function getNamespace(): string
    {
        $graphQLServerContextID = AppThreadHelper::getGraphQLServerContextUniqueID($this->pluginAppGraphQLServerContext);
        return $this->makeNamespace(
            $this->getNamespaceTimestampPrefix(),
            'internal_' . $graphQLServerContextID
        );
    }
}
