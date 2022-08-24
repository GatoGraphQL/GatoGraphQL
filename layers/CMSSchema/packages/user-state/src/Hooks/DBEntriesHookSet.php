<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserState\Hooks;

use PoP\ComponentModel\Response\DatabaseEntryManager;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\UserState\FieldResolvers\ObjectType\GlobalObjectTypeFieldResolver;

class DBEntriesHookSet extends AbstractHookSet
{
    private ?GlobalObjectTypeFieldResolver $globalObjectTypeFieldResolver = null;

    final public function setGlobalObjectTypeFieldResolver(GlobalObjectTypeFieldResolver $globalObjectTypeFieldResolver): void
    {
        $this->globalObjectTypeFieldResolver = $globalObjectTypeFieldResolver;
    }
    final protected function getGlobalObjectTypeFieldResolver(): GlobalObjectTypeFieldResolver
    {
        /** @var GlobalObjectTypeFieldResolver */
        return $this->globalObjectTypeFieldResolver ??= $this->instanceManager->getInstance(GlobalObjectTypeFieldResolver::class);
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
            'PoPCMSSchema\UserState\DataloaderHooks:metaFields',
            $this->getGlobalObjectTypeFieldResolver()->getFieldNamesToResolve()
        );
        return $dbNameToFieldNames;
    }
}
