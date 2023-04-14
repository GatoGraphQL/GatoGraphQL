<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConfigurationCache;

class InternalGraphQLServerContainerCacheConfigurationManager extends ContainerCacheConfigurationManager
{
    /**
     * The internal server is always private, and has the
     * same configuration as the default admin endpoint.
     */
    public function getNamespace(): string
    {
        return $this->makeNamespace($this->getNamespaceTimestampPrefix(), 'internal');
    }
}
