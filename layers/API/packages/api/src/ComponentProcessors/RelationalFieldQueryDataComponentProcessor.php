<?php

declare(strict_types=1);

namespace PoPAPI\API\ComponentProcessors;

class RelationalFieldQueryDataComponentProcessor extends AbstractRelationalFieldQueryDataComponentProcessor
{
    public final const COMPONENT_LAYOUT_RELATIONALFIELDS = 'layout-relationalfields';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_RELATIONALFIELDS],
        );
    }
}
