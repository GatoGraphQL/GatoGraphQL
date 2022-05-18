<?php

declare(strict_types=1);

namespace PoP\ComponentRouting;

interface RouteModuleProcessorManagerInterface
{
    public function addRouteModuleProcessor(AbstractRouteModuleProcessor $processor): void;
    /**
     * @return AbstractRouteModuleProcessor[]
     */
    public function getProcessors(string $group = null): array;
    public function getDefaultGroup(): string;
    /**
     * @return string[]|null
     */
    public function getRouteModuleByMostAllmatchingVarsProperties(string $group = null): ?array;
}
