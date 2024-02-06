<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container;

use GatoGraphQL\GatoGraphQL\StateManagers\AppThreadHelper;

trait InternalGraphQLServerContainerBuilderFactoryTrait
{
    /**
     * @param array<string,mixed> $pluginAppGraphQLServerContext
     */
    public function getInternalGraphQLServerContainerClassName(
        array $pluginAppGraphQLServerContext,
        string $containerClassName,
    ): string {
        return 'InternalGraphQLServer_' . AppThreadHelper::getGraphQLServerContextUniqueID($pluginAppGraphQLServerContext) . '_' . $containerClassName;
    }
}
