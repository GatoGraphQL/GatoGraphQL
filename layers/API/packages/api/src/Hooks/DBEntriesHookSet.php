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

    public function moveEntriesUnderDBName(array $dbname_datafields): array
    {
        // Enable to add all fields starting with "__" (such as "__schema") as meta
        $dbname_datafields['meta'] = App::applyFilters(
            'PoPAPI\API\DataloaderHooks:metaFields',
            [
                'fullSchema',
                'typeName',
            ]
        );
        $dbname_datafields['context'] = App::applyFilters(
            'PoPAPI\API\DataloaderHooks:contextFields',
            [
                'var',
                'context',
            ]
        );
        return $dbname_datafields;
    }
}
