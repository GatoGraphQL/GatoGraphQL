<?php

declare(strict_types=1);

namespace PoP\ModuleRouting;

interface RouteModuleProcessorManagerInterface
{
    public function addRouteModuleProcessor(AbstractRouteModuleProcessor $processor): void;
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
