<?php

declare(strict_types=1);

namespace PoPCMSSchema\QueriedObject\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\Root\App;

trait QueriedDBObjectComponentProcessorTrait
{
    protected function getQueriedDBObjectID(): string|int|null
    {
        return App::getState(['routing', 'queried-object-id']) ?? null;
    }
}
