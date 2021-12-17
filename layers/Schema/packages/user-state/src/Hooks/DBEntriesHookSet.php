<?php

declare(strict_types=1);

namespace PoPSchema\UserState\Hooks;

use PoP\BasicService\AbstractHookSet;
use PoPSchema\UserState\FieldResolvers\ObjectType\GlobalObjectTypeFieldResolver;

class DBEntriesHookSet extends AbstractHookSet
{
    private ?GlobalObjectTypeFieldResolver $globalObjectTypeFieldResolver = null;

    final public function setGlobalObjectTypeFieldResolver(GlobalObjectTypeFieldResolver $globalObjectTypeFieldResolver): void
    {
        $this->globalObjectTypeFieldResolver = $globalObjectTypeFieldResolver;
    }
    final protected function getGlobalObjectTypeFieldResolver(): GlobalObjectTypeFieldResolver
    {
        return $this->globalObjectTypeFieldResolver ??= $this->instanceManager->getInstance(GlobalObjectTypeFieldResolver::class);
    }

    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            'PoP\ComponentModel\Engine:moveEntriesUnderDBName:dbName-dataFields',
            array($this, 'moveEntriesUnderDBName'),
            10,
            1
        );
    }

    public function moveEntriesUnderDBName(array $dbname_datafields): array
    {
        $dbname_datafields['userstate'] = $this->getHooksAPI()->applyFilters(
            'PoPSchema\UserState\DataloaderHooks:metaFields',
            $this->getGlobalObjectTypeFieldResolver()->getFieldNamesToResolve()
        );
        return $dbname_datafields;
    }
}
