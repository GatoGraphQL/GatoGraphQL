<?php

declare(strict_types=1);

namespace PoP\ComponentRouting;

abstract class AbstractEntryComponentRoutingProcessor extends AbstractComponentRoutingProcessor
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
