<?php

declare(strict_types=1);

namespace PoP\API\PersistedQueries;

class PersistedQueryManager extends AbstractPersistedQueryManager
{
    protected function addQueryResolutionToSchema(): bool
    {
        return true;
    }
}
