<?php

declare(strict_types=1);

namespace PoPCMSSchema\QueriedObject\ComponentProcessors;

use PoP\Root\App;

trait QueriedDBObjectComponentProcessorTrait
{
    protected function getQueriedDBObjectID(\PoP\ComponentModel\Component\Component $component, array &$props, &$data_properties): string | int | array | null
    {
        return App::getState(['routing', 'queried-object-id']) ?? null;
    }
}
