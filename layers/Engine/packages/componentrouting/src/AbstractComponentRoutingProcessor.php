<?php

declare(strict_types=1);

namespace PoP\ComponentRouting;

use PoP\Root\Services\AbstractBasicService;

abstract class AbstractComponentRoutingProcessor extends AbstractBasicService implements ComponentRoutingProcessorInterface
{
    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return array();
    }

    /**
     * @return array<string,array<string,array<array<string,mixed>>>>
     */
    public function getStatePropertiesToSelectComponentByNatureAndRoute(): array
    {
        return array();
    }

    /**
     * @return array<string,array<array<string,mixed>>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        return array();
    }

    /**
     * @return array<array<string,mixed>>
     */
    public function getStatePropertiesToSelectComponent(): array
    {
        return array();
    }
}
