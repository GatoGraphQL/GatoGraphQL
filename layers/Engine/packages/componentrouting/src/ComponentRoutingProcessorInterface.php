<?php

declare(strict_types=1);

namespace PoP\ComponentRouting;

use PoP\ComponentModel\Component\Component;

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
     * @return array<array<string,mixed>>
     */
    public function getStatePropertiesToSelectComponent(): array;
}
