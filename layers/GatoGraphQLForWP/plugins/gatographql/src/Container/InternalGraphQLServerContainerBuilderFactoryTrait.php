<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container;

use GatoGraphQL\GatoGraphQL\Facades\StateManagers\AppThreadServiceFacade;

trait InternalGraphQLServerContainerBuilderFactoryTrait
{
    public function getInternalGraphQLServerContainerClassName(
        array $pluginAppGraphQLServerContext,
        string $containerClassName,
    ): string {
        $appThreadService = AppThreadServiceFacade::getInstance();
        return 'InternalGraphQLServer_' . $appThreadService->getGraphQLServerContextUniqueID($pluginAppGraphQLServerContext) . '_' . $containerClassName;
    }
}
