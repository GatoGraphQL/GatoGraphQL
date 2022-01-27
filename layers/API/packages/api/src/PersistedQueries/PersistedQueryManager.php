<?php

declare(strict_types=1);

namespace PoPAPI\API\PersistedQueries;

class PersistedQueryManager extends AbstractPersistedQueryManager
{
    protected function addQueryResolutionToSchema(): bool
    {
        return true;
    }
}
