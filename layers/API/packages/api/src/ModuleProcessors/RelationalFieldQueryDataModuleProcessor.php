<?php

declare(strict_types=1);

namespace PoPAPI\API\ModuleProcessors;

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
