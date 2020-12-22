<?php

declare(strict_types=1);

namespace PoP\API\ModuleProcessors;

use PoP\API\ModuleProcessors\AbstractRelationalFieldQueryDataModuleProcessor;

class RelationalFieldQueryDataModuleProcessor extends AbstractRelationalFieldQueryDataModuleProcessor
{
    public const MODULE_LAYOUT_RELATIONALFIELDS = 'layout-relationalfields';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_RELATIONALFIELDS],
        );
    }
}
