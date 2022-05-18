<?php

declare(strict_types=1);

namespace PoPAPI\API\ComponentProcessors;

class RelationalFieldQueryDataComponentProcessor extends AbstractRelationalFieldQueryDataComponentProcessor
{
    public final const MODULE_LAYOUT_RELATIONALFIELDS = 'layout-relationalfields';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_RELATIONALFIELDS],
        );
    }
}
