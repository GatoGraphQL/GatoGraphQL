<?php

declare(strict_types=1);

namespace PoP\ComponentRouting;

interface ComponentRoutingProcessorManagerInterface
{
    public function addComponentRoutingProcessor(AbstractComponentRoutingProcessor $processor): void;
    /**
     * @return AbstractComponentRoutingProcessor[]
     */
    public function getProcessors(string $group = null): array;
    public function getDefaultGroup(): string;
    /**
     * @return string[]|null
     */
    public function getRouteModuleByMostAllmatchingVarsProperties(string $group = null): ?array;
}
