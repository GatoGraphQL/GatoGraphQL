<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container;

use PoP\Root\Container\SystemContainerBuilderFactory;

class InternalGraphQLServerSystemContainerBuilderFactory extends SystemContainerBuilderFactory
{
    use InternalGraphQLServerContainerBuilderFactoryTrait;

    /**
     * @param array<string,mixed> $pluginAppGraphQLServerContext
     */
    public function __construct(
        private array $pluginAppGraphQLServerContext,
    ){        
    }

    public function getContainerClassName(): string
    {
        return 'InternalGraphQLServer_' . $this->getContextUniqueID($this->pluginAppGraphQLServerContext) . '_' . parent::getContainerClassName();
    }
}
