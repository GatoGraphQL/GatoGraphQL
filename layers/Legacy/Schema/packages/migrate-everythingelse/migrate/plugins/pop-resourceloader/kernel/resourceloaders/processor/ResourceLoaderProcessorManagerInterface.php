<?php

declare(strict_types=1);

interface ResourceLoaderProcessorManagerInterface
{
    /**
     * @deprecated Use the Service Container instead
     */
    public function overrideProcessorClass(string $overrideClass, string $withClass, array $forItemNames): void;
    public function getProcessor(array $item): PoP_ResourceLoaderProcessor;
    public function getLoadedItems(): array;
    public function getLoadedItemFullNameProcessorInstances(): array;
}
