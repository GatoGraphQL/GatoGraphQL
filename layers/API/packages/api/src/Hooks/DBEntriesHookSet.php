<?php

declare(strict_types=1);

namespace PoPAPI\API\Hooks;

use PoPAPI\API\Constants\HookNames;
use PoP\ComponentModel\Response\DatabaseEntryManager;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

class DBEntriesHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            DatabaseEntryManager::HOOK_DBNAME_TO_FIELDNAMES,
            $this->moveEntriesUnderDBName(...),
            10,
            1
        );
    }

    /**
     * @param array<string,string[]> $dbNameToFieldNames
     * @return array<string,string[]>
     */
    public function moveEntriesUnderDBName(array $dbNameToFieldNames): array
    {
        // Enable to add all fields starting with "__" (such as "__schema") as meta
        $dbNameToFieldNames['meta'] = App::applyFilters(
            HookNames::MOVE_ENTRIES_UNDER_DB_NAME_META_FIELDS,
            [
                'fullSchema',
                'typeName',
            ]
        );
        $dbNameToFieldNames['context'] = App::applyFilters(
            HookNames::MOVE_ENTRIES_UNDER_DB_NAME_CONTEXT_FIELDS,
            [
                'var',
                'context',
            ]
        );
        return $dbNameToFieldNames;
    }
}
