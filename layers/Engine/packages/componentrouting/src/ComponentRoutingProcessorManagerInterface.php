<?php

declare(strict_types=1);

namespace PoP\ComponentRouting;

use PoP\ComponentModel\Component\Component;
interface ComponentRoutingProcessorManagerInterface
{
    public function addComponentRoutingProcessor(ComponentRoutingProcessorInterface $processor): void;
    /**
     * @return ComponentRoutingProcessorInterface[]
     */
    public function getProcessors(string $group = null): array;
    public function getDefaultGroup(): string;
    public function getRoutingComponentByMostAllMatchingStateProperties(string $group = null): ?Component;
}
