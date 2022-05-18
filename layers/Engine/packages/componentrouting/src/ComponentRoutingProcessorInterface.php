<?php

declare(strict_types=1);

namespace PoP\ComponentRouting;

interface ComponentRoutingProcessorInterface
{
    /**
     * @return string[]
     */
    public function getGroups(): array;

    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array;

    /**
     * @return array<string, array<array>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array;

    /**
     * @return array<array<string, string[]>>
     */
    public function getStatePropertiesToSelectComponent(): array;
}
