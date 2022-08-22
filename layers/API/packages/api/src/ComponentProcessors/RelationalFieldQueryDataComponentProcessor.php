<?php

declare(strict_types=1);

namespace PoPAPI\API\ComponentProcessors;

class RelationalFieldQueryDataComponentProcessor extends AbstractRelationalFieldQueryDataComponentProcessor
{
    public final const COMPONENT_LAYOUT_RELATIONALFIELDS = 'layout-relationalfields';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_RELATIONALFIELDS,
        );
    }
}
