<?php

declare(strict_types=1);

namespace PoP\ComponentRouting;

interface ComponentRoutingProcessorManagerInterface
{
    public function addComponentRoutingProcessor(ComponentRoutingProcessorInterface $processor): void;
    /**
     * @return ComponentRoutingProcessorInterface[]
     */
    public function getProcessors(string $group = null): array;
    public function getDefaultGroup(): string;
    /**
     * @return string[]|null
     */
    public function getRoutingComponentByMostAllMatchingStateProperties(string $group = null): ?array;
}
