<?php

declare(strict_types=1);

namespace PoPCMSSchema\QueriedObject\ModuleProcessors;

use PoP\Root\App;

trait QueriedDBObjectModuleProcessorTrait
{
    protected function getQueriedDBObjectID(array $module, array &$props, &$data_properties): string | int | array | null
    {
        return App::getState(['routing', 'queried-object-id']) ?? null;
    }
}
