<?php

declare(strict_types=1);

namespace PoPCMSSchema\QueriedObject\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\Root\App;

trait QueriedDBObjectComponentProcessorTrait
{
    protected function getQueriedDBObjectID(Component $component, array &$props, &$data_properties): string | int | array | null
    {
        return App::getState(['routing', 'queried-object-id']) ?? null;
    }
}
