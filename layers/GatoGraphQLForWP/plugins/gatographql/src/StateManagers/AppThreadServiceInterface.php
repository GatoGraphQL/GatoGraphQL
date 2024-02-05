<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StateManagers;

interface AppThreadServiceInterface
{
    /**
     * @param array<string,mixed> $pluginAppGraphQLServerContext
     */
    public function getGraphQLServerContextUniqueID(array $pluginAppGraphQLServerContext): string;
}
