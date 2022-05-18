<?php

declare(strict_types=1);

namespace PoP\ComponentRouting;

abstract class AbstractEntryRouteModuleProcessor extends AbstractRouteModuleProcessor
{
    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return [
            ComponentRoutingGroups::ENTRYCOMPONENT,
        ];
    }
}
