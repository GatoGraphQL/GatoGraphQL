<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserState\Hooks;

use PoP\ComponentModel\Response\DatabaseEntryManager;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\UserState\Constants\HookNames;
use PoPCMSSchema\UserState\FieldResolvers\ObjectType\ObjectTypeFieldResolver;

class DBEntriesHookSet extends AbstractHookSet
{
    private ?ObjectTypeFieldResolver $globalObjectTypeFieldResolver = null;

    final protected function getObjectTypeFieldResolver(): ObjectTypeFieldResolver
    {
        if ($this->globalObjectTypeFieldResolver === null) {
            /** @var ObjectTypeFieldResolver */
            $globalObjectTypeFieldResolver = $this->instanceManager->getInstance(ObjectTypeFieldResolver::class);
            $this->globalObjectTypeFieldResolver = $globalObjectTypeFieldResolver;
        }
        return $this->globalObjectTypeFieldResolver;
    }

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
        $dbNameToFieldNames['userstate'] = App::applyFilters(
            HookNames::MOVE_ENTRIES_UNDER_DB_NAME_META_FIELDS,
            $this->getObjectTypeFieldResolver()->getFieldNamesToResolve()
        );
        return $dbNameToFieldNames;
    }
}
