<?php

declare(strict_types=1);

namespace PoP\ModuleRouting;

abstract class AbstractEntryRouteModuleProcessor extends AbstractRouteModuleProcessor
{
    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return [
            ModuleRoutingGroups::ENTRYCOMPONENT,
        ];
    }
}
