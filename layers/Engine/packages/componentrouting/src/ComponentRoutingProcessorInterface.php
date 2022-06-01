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
     * @return array<string,array<string,array<array<string,mixed>>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array;

    /**
     * @return array<string,array<array<string,mixed>>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array;

    /**
     * @return array<array<string,mixed>>
     */
    public function getStatePropertiesToSelectComponent(): array;
}
