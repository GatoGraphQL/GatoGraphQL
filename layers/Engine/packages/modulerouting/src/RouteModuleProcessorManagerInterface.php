<?php

declare(strict_types=1);

namespace PoP\ModuleRouting;

use PoP\ModuleRouting\AbstractRouteModuleProcessor;
use PoP\Root\Registries\ServiceDefinitionIDRegistryInterface;

interface RouteModuleProcessorManagerInterface extends ServiceDefinitionIDRegistryInterface
{
    /**
     * @return AbstractRouteModuleProcessor[]
     */
    public function getProcessors(string $group = null): array;
    public function getDefaultGroup(): string;
    /**
     * @return array<string, mixed>
     */
    public function getVars(): array;
    /**
     * @return string[]|null
     */
    public function getRouteModuleByMostAllmatchingVarsProperties(string $group = null): ?array;
}
