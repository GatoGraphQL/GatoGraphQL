<?php

declare(strict_types=1);

namespace PoP\ComponentRouting;

use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractComponentRoutingProcessor implements ComponentRoutingProcessorInterface
{
    use BasicServiceTrait;

    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return array();
    }

    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        return array();
    }

    /**
     * @return array<string, array<array>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        return array();
    }

    /**
     * @return array<array<string, string[]>>
     */
    public function getStatePropertiesToSelectComponent(): array
    {
        return array();
    }
}
