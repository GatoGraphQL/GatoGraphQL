<?php

declare(strict_types=1);

namespace PoPAPI\API\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

class DBEntriesHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            'PoP\ComponentModel\Engine:moveEntriesUnderDBName:dbName-dataFields',
            $this->moveEntriesUnderDBName(...),
            10,
            1
        );
    }

    public function moveEntriesUnderDBName(array $dbNameToFieldNames): array
    {
        // Enable to add all fields starting with "__" (such as "__schema") as meta
        $dbNameToFieldNames['meta'] = App::applyFilters(
            'PoPAPI\API\DataloaderHooks:metaFields',
            [
                'fullSchema',
                'typeName',
            ]
        );
        $dbNameToFieldNames['context'] = App::applyFilters(
            'PoPAPI\API\DataloaderHooks:contextFields',
            [
                'var',
                'context',
            ]
        );
        return $dbNameToFieldNames;
    }
}
